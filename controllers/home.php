<?php
/**
 * Author: CHEVALIER Alexis <alexis.chevalier@supinfo.com>
 * Date: 04/02/13
 * File: home.php
 */

class Home_Controller
{

    /**
     * Accueil du site
     *
     * @param array $args the url variables posted to index.php
     */
    public function index(array $args)
    {
        $url_model = new Urls_Model();
        $view = new View_Model('home');
        if (($messages = FlashMessages::get()) != null) {
            $view->assign('messages', $messages);
        }

        if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
            $view->assign('urls', $url_model->getUserUrls($_SESSION['user_id']));
        }
    }

    /**
     * Redirection d'une url raccourcie
     * @param array $args
     */
    public function shortened_url(array $args)
    {
        $url_model = new Urls_Model();
        $url = $url_model->RedirectUrl(Url::base62_decode($args['url']));
        if ($url == null) {
            FlashMessages::add("error", "This short URL is invalid.");
            header('Location: ' . SITE_ROOT );
        } else if ($url == 'disabled') {
            FlashMessages::add("error", "This short URL has been disabled.");
            header('Location: ' . SITE_ROOT );
        } else {
            header('Location: ' . $url->getUrlLong(), true, 301);
        }
    }

    /**
     * Page des statistiques
     * @param array $args
     */
    public function stats_url(array $args)
    {
        $stats = new Stats_Model();
        $results = $stats->getStats(Url::base62_decode($args['url']));
        $view = new View_Model('stats');
        $view->assign('stats', $results);
        $view->assign('url', $args['url']);
    }

    /**
     * Raccourcit une url
     * @param array $args
     */
    public function shorten(array $args)
    {
        $urls = new Urls_Model();
        $return = $urls->storeUrl(InputLoader::post('url_long'));
        if (is_array($return)) {
            FlashMessages::add('error', 'Please type a valid URL');
            header('Location: ' . SITE_ROOT );
        } else {
            header('Location: ' . $return->getShort() . '/stats');
        }
    }

    /**
     * Page d'inscription
     * @param array $args
     */
    public function signup(array $args)
    {
        if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
            FlashMessages::add('error', 'You are already logged in !');
            header('Location: ' . SITE_ROOT );
        }
        $users = new Users_Model();
        $post = InputLoader::Post();
        if (isset($post['submit_signup'])) {
            if (!is_array($result = $users->register($post['username'], $post['password'], $post['password_check']))) {
                header('Location: ' . SITE_ROOT );
            } else {
                $view = new View_Model('signup');
                $view->assign('errors', $result);
                if (!empty($post['username'])) {
                    $view->assign('username', stripslashes($post['username']));
                } else {
                    $view->assign('username', '');
                }
            }
        } else {
            $view = new View_Model('signup');
        }
    }

    /**
     * Opération de déconnexion
     */
    public function logout()
    {
        if (!isset($_SESSION['logged']) || $_SESSION['logged'] == false) {
            header('Location: ' . SITE_ROOT );
            FlashMessages::add('error', 'You must be logged to access to this page !');
        }
        $users = new Users_Model();
        $users->logout();

        header('Location: ' . SITE_ROOT );
    }

    /**
     * Page de connexion
     * @param array $args
     */
    public function login(array $args)
    {
        if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
            FlashMessages::add('error', 'You are already logged in !');
            header('Location: ' . SITE_ROOT );
        }
        $users = new Users_Model();
        $post = InputLoader::Post();
        if (isset($post['submit_login'])) {
            if (!is_array($result = $users->check_login($post['username'], $post['password']))) {
                header('Location: ' . SITE_ROOT );
            } else {
                $view = new View_Model('login');
                $view->assign('errors', $result);
                if (!empty($post['username'])) {
                    $view->assign('username', stripslashes($post['username']));
                } else {
                    $view->assign('username', '');
                }
            }
        } else {
            $view = new View_Model('login');
        }
    }

    /**
     * Validation de compte
     * @param array $args
     */
    public function validate(array $args)
    {
        if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
            FlashMessages::add('error', 'You are already logged in !');
            header('Location: ' . SITE_ROOT );
        }
        $user_model = new Users_Model();
        $result = $user_model->validate_account($args['id'], $args['hash']);
        if ($result == true) {
            header('Location: ' . SITE_ROOT );
        } else {
            FlashMessages::add('error', 'Bad validation id/key');
            header('Location: ' . SITE_ROOT );
        }
    }

    /**
     * Edition de compte
     * @param array $args
     */
    public function editAccount(array $args)
    {

        if (!isset($_SESSION['logged']) || $_SESSION['logged'] == false) {
            header('Location: ' . SITE_ROOT );
            FlashMessages::add('error', 'You must be logged to access to this page !');
        }
        $view = new View_Model('userEdit');

        if (($messages = FlashMessages::get()) != null) {
            $view->assign('messages', $messages);
        }
        $users = new Users_Model();
        if (InputLoader::post('update_account') == 'update_account') {
            if (InputLoader::Post('password') != '' && InputLoader::Post('password') == InputLoader::Post('passwordCheck')) {
                if ($users->update_user($_SESSION['user_id'], InputLoader::Post('password')) == true) {
                    FlashMessages::add('error', 'Password successfully updated !');
                    header('Location: ' . SITE_ROOT . 'user/options');
                } else {
                    FlashMessages::add('error', 'An error occured !');
                    header('Location: ' . SITE_ROOT . 'user/options');
                }
            } else {
                FlashMessages::add('error', 'Password isn\'t valid');
                header('Location: ' . SITE_ROOT . 'user/options');
            }
        } else if (InputLoader::post('delete_account') == 'delete_account') {
            if ($users->delete_account($_SESSION['user_id'])) {
                FlashMessages::add('error', 'Account deleted !');
                $users->logout();
                header('Location: ' . SITE_ROOT );
            } else {
                FlashMessages::add('error', 'An error occured !');
                header('Location: ' . SITE_ROOT . 'user/options');
            }
        }
    }

    /**
     * Page 404
     * @param array $args
     */
    public function _404(array $args)
    {
        $view = new View_Model("404");
        $view->assign('error', $args['error']);
    }
}
