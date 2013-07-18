<?php
/**
 * Author: CHEVALIER Alexis <alexis.chevalier@supinfo.com>
 * Date: 16/02/13
 * File: urls.php
 */

class Urls_Model
{
    /**
     *
     * Vérifie le propriétaire d'une URL
     * @param $url_id
     * @param $user_id
     * @param bool $strict
     * @return bool
     */
    public static function checkUrlOwner($url_id, $user_id, $strict = false)
    {
        $pdo = Db::get_pdo_instance();
        if ($strict == false) {
            $sth = $pdo->prepare('SELECT count(*) as count FROM 2web_urls WHERE id = :urlid AND (user_id = :userid || user_id = -1)');
        } else {
            $sth = $pdo->prepare('SELECT count(*) as count FROM 2web_urls WHERE id = :urlid AND user_id = :userid');
        }

        $sth->bindParam("urlid", $url_id);
        $sth->bindParam("userid", $user_id);
        $sth->execute();
        $rows = $sth->fetch(PDO::FETCH_ASSOC);
        $sth->closeCursor();
        if ($rows['count'] == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Vérifie la validité d'une URL
     * @param $url
     * @return bool
     */
    public function checkUrl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_exec($ch);
        if (curl_errno($ch) != 0) {
            curl_close($ch);
            return false;
        } else {
            curl_close($ch);
            return true;
        }
    }

    /**
     * Enregistre une URL
     * @param $url
     * @return array|Url
     */
    public function storeUrl($url)
    {
        if ($this->checkUrl($url) == false) {
            return Array('The url is invalid');
        } else {
            //Définition du login
            if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
                $userid = $_SESSION['user_id'];
            } else {
                $userid = -1;
            }
            //Définition du temps
            $time = new DateTime();
            $time = $time->getTimestamp();
            $disallowed = array('http://', 'https://', 'www.');
            $name = str_replace($disallowed, "", $url);

            $pdo = Db::get_pdo_instance();
            $sth2 = $pdo->prepare('INSERT INTO 2web_urls VALUES(null, :url, :userid, :date_insert, 1, :name, 0)');
            if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
                $url = "http://" . $url;
            }
            $sth2->bindParam("url", $url);
            $sth2->bindParam("userid", $userid);
            $sth2->bindParam("date_insert", $time);
            $sth2->bindParam('name', $name);
            $sth2->execute();
            $id = $pdo->lastInsertId();
            $sth2->closeCursor();
            return new Url($time, $id, $url, $userid, 1, $name, 0);
        }
    }

    /**
     * Retourne toutes les urls d'un membre
     * @param $id
     * @return array|null
     */
    public function getUserUrls($id)
    {
        $result = Array();
        $pdo = Db::get_pdo_instance();
        $sth = $pdo->prepare('SELECT *
                       FROM 2web_urls
                       WHERE user_id = :id
                       ORDER BY date_created DESC');
        $sth->bindParam("id", $id);
        $sth->execute();
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Url($row['date_created'], $row['id'], $row['url_long'], $row['user_id'], $row['enabled'], $row['name'], $row['clicked_count']);
        }
        $sth->closeCursor();
        if (sizeof($result) == 0) {
            $result = null;
        }
        return $result;
    }

    /**
     * Retourne une url
     * @param $id
     * @return Url
     */
    public function getUrl($id)
    {
        $pdo = Db::get_pdo_instance();
        $sth = $pdo->prepare('SELECT *
                       FROM 2web_urls
                       WHERE id = :id');
        $sth->bindParam("id", $id);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        $result = new Url($row['date_created'], $row['id'], $row['url_long'], $row['user_id'], $row['enabled'], $row['name'], $row['clicked_count']);
        $sth->closeCursor();
        return $result;
    }

    /**
     * Etape précédant la redirection de l'url
     * @param $id
     * @return null|string|Url
     */
    public function redirectUrl($id)
    {
        $pdo = Db::get_pdo_instance();
        $sth = $pdo->prepare('SELECT count(*) AS count, date_created, id, url_long, user_id, enabled, name, clicked_count
                       FROM 2web_urls
                       WHERE id = :id');
        $sth->bindParam("id", $id);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        $sth->closeCursor();
        if ($row['count'] == 1) {
            if ($row['enabled'] == 0) {
                return 'disabled';
            }
            $result = new Url($row['date_created'], $row['id'], $row['url_long'], $row['user_id'], $row['enabled'], $row['name'], $row['clicked_count']);

            if (isset($_SERVER['HTTP_REFERER'])) {
                $referer = $_SERVER['HTTP_REFERER'];
            } else {
                $referer = 'direct';
            }

            if (isset($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            } else {
                $ip = null;
            }
            $result->addOneClick();
            $stat = new Stat($id, $ip, $referer);
            $stat->insert();
            return $result;
        } else {
            return null;
        }
    }
}
