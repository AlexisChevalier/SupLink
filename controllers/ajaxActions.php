<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alexischevalier
 * Date: 23/02/13
 * Time: 13:16
 * To change this template use File | Settings | File Templates.
 */
class AjaxActions_Controller
{
    /**
     * Permet la suppression d'une URL via AJAX
     * @param array $args
     */
    public function deleteUrl(array $args)
    {
        if (isset($_POST['id'])) {
            $result = Url::delete($_POST['id'], $_SESSION['user_id']);
            echo $result;
            if ($result == true) {
                echo 'deleted';
            } else {
                echo 'err_delete_failed';
            }
        } else {
            echo 'err_delete_failed';
        }
    }

    /**
     * Permet l'activation d'une URL via AJAX
     * @param array $args
     */
    public function enableUrl(array $args)
    {
        if (isset($_POST['id'])) {
            $result = Url::enable($_POST['id'], $_SESSION['user_id']);
            echo $result;
            if ($result == true) {
                echo 'enabled';
            } else {
                echo 'err_enable_failed';
            }
        } else {
            echo 'err_enable_failed';
        }
    }

    /**
     * Permet la désactivation d'une url via AJAX
     * @param array $args
     */
    public function disableUrl(array $args)
    {
        if (isset($_POST['id'])) {
            $result = Url::disable($_POST['id'], $_SESSION['user_id']);
            if ($result == true) {
                echo 'deleted';
            } else {
                echo 'err_disable_failed';
            }
        } else {
            echo 'err_disable_failed';
        }
    }

    /**
     * Permet de changer le nom d'une URL Via AJAX
     * @param array $args
     */
    public function editUrl(array $args)
    {
        if (isset($_POST['id']) && isset($_POST['value'])) {
            $exploded = explode('_', $_POST['id']);
            $result = Url::update($exploded[1], $_POST['value'], $_SESSION['user_id']);
            if ($result == true) {
                echo $_POST['value'];
            } else {
                echo 'err_update_failedd';
            }
        } else {
            echo 'err_update_failed';
        }
    }

    /**
     * Renvoie l'URL Raccourcie via AJAX
     * @param array $args
     */
    public function shortenUrl(array $args)
    {
        if (isset($_POST['url'])) {
            $urls = new Urls_Model();
            $return = $urls->storeUrl(InputLoader::post('url'));
            if (is_array($return)) {
                echo 'err_url_invalid';
            } else {
                echo SITE_ROOT . $return->getShort();
            }
        } else {
            echo 'err_url_invalid';
        }
    }
}
