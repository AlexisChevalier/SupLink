<?php
/**
 * Author: CHEVALIER Alexis <alexis.chevalier@supinfo.com>
 * Date: 16/02/13
 * File: users.php
 */

class Users_Model
{
    /**
     *
     * Enregistre un utilisateur
     * @param $username
     * @param $password
     * @param $password_check
     * @return array|string
     */
    public function register($username, $password, $password_check)
    {
        $pdo = Db::get_pdo_instance();
        $username_valid = $username_already_exists = $password_valid = false;
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $username_valid = true;
            if ($username_valid == true) {
                $sth = $pdo->prepare('SELECT COUNT(*) as count_users
                       FROM 2web_users
                       WHERE email = :username');
                $sth->bindParam("username", $username);
                $sth->execute();
                if ($sth->fetchColumn() > 0) {
                    $username_already_exists = true;
                }
                $sth->closeCursor();
            }
        }
        if (!empty($password) && $password == $password_check) {
            $password_valid = true;
        }

        if ($username_valid == true && $password_valid == true && $username_already_exists == false) {
            $uniqid = uniqid();
            $sth2 = $pdo->prepare('INSERT INTO 2web_users VALUES(null,:username, :password, :uniqid)');
            $sth2->bindParam("username", $username);
            $hash = crypt($password, Config::Get("salt_hash"));
            $sth2->bindParam("password", $hash);
            $sth2->bindParam("uniqid", $uniqid);
            $sth2->execute();
            $id = $pdo->lastInsertId();
            $sth2->closeCursor();
            FlashMessages::add('registered', 'We just sent an email to your address, please validate your account before using our service !');

            /* Sends mail to user */
            $mailer = Mail::getMailerInstance();

            // Create a message
            $message = Swift_Message::newInstance('Please validate your account')
                ->setFrom(array('validation@heavenshorten.er' => 'HeavenShortener Validation System'))
                ->setTo(array($username))
                ->setBody('Welcome on HeavenShortener, Please validate your account by using the following link : ' . SITE_ROOT . 'validate/' . $id . '/' . $uniqid);

            // Send the message
            $result = $mailer->send($message);

            return 'ok';
        } else {
            $errors_form = array();
            if ($username_valid == false) {
                $errors_form[] = "Please type a valid email address.";
            }
            if ($username_already_exists == true) {
                $errors_form[] = "An account with this email address already exists.";
            }
            if ($password_valid == false) {
                if (empty($password)) {
                    $errors_form[] = "Password cannot be empty.";
                } else {
                    $errors_form[] = "Passwords doesn't match.";
                }
            }
            return $errors_form;
        }
    }

    /**
     * Valide un compte
     * @param $id
     * @param $hash
     * @return bool
     */
    public function validate_account($id, $hash)
    {
        $pdo = Db::get_pdo_instance();
        $sth = $pdo->prepare('SELECT COUNT(*) as count_row, id, email FROM 2web_users WHERE id = :id AND valid = :hash');
        $sth->bindParam("id", $id);
        $sth->bindParam("hash", $hash);
        $sth->execute();
        $rows = $sth->fetch(PDO::FETCH_ASSOC);
        $sth->closeCursor();
        if ($rows['count_row'] == 1) {
            $sth2 = $pdo->prepare('UPDATE 2web_users SET valid = "1" WHERE id = :id');
            $sth2->bindParam("id", $id);
            $sth2->execute();
            $this->perform_login($rows['id'], $rows['email']);
            return true;
        } else {
            return false;
        }
    }

    /**
     *  Procéde a la déconnexion
     */
    public function logout()
    {
        $_SESSION['logged'] = false;
        $_SESSION['user_id'] = null;
        $_SESSION['user_email'] = null;
        FlashMessages::add('logout', 'You are logged out !');
    }

    /**
     * Supprime un compte
     * @param $id
     * @return bool
     */
    public function delete_account($id)
    {
        if ($_SESSION['user_id'] == $id) {
            $pdo = Db::get_pdo_instance();
            $sth2 = $pdo->prepare('DELETE user,url,stat FROM 2web_users user
                                    LEFT JOIN 2web_urls url ON url.user_id=user.id
                                    LEFT JOIN 2web_stats stat ON stat.id_url=url.id
                                    WHERE user.id = :id');
            $sth2->bindParam("id", $id);
            $sth2->execute();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Met a jour un compte
     * @param $id
     * @param $password
     * @return bool
     */
    public function update_user($id, $password)
    {
        if ($_SESSION['user_id'] == $id) {
            $pdo = Db::get_pdo_instance();
            $sth2 = $pdo->prepare('UPDATE 2web_users SET password = :password WHERE id = :id');
            $hash = crypt($password, Config::Get("salt_hash"));
            $sth2->bindParam("password", $hash);
            $sth2->bindParam("id", $id);
            $sth2->execute();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Vérifie un compte
     * @param $username
     * @param $password
     * @return array|bool
     */
    public function check_login($username, $password)
    {
        $pdo = Db::get_pdo_instance();
        $username_valid = $password_valid = false;
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $username_valid = true;
        }
        if (!empty($password)) {
            $password_valid = true;
        }

        if ($username_valid == true && $password_valid == true) {
            $sth2 = $pdo->prepare('SELECT COUNT(*) as count_row, id, email, valid FROM 2web_users WHERE email = :username AND password = :password');
            $sth2->bindParam("username", $username);
            $hash = crypt($password, Config::Get("salt_hash"));
            $sth2->bindParam("password", $hash);
            $sth2->execute();
            $rows = $sth2->fetch(PDO::FETCH_ASSOC);
            $sth2->closeCursor();
            if ($rows["count_row"] == 1) {
                if ($rows['valid'] != 1) {
                    return Array('Please validate your account first !');
                } else {
                    $this->perform_login($rows['id'], $rows['email']);
                    return true;
                }
            } else {
                return Array('Bad email/password.');
            }

        } else {
            $errors_form = array();
            if ($username_valid == false) {
                $errors_form[] = "Please type a valid email address.";
            }
            if ($password_valid == false) {
                $errors_form[] = "Password cannot be empty.";
            }
            return $errors_form;
        }
    }

    /**
     * Process login
     * @param $id
     * @param $username
     */
    public function perform_login($id, $username)
    {
        $_SESSION['logged'] = true;
        $_SESSION['user_id'] = $id;
        $_SESSION['user_email'] = $username;
        FlashMessages::add('logout', 'You are logged in !');
    }
}
