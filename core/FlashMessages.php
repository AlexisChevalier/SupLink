<?php
/**
 * Author: CHEVALIER Alexis <alexis.chevalier@supinfo.com>
 * Date: 17/02/13
 * File: FlashMessages.php
 */

new FlashMessages();

class FlashMessages
{
    function __construct()
    {
        if (isset($_SESSION['flashMessages'])) {

        } else {
            $_SESSION['flashMessages'] = Array();
        }
    }

    public static function add($key, $message)
    {
        $_SESSION['flashMessages'][$key] = $message;
    }

    public static function get($key = null)
    {
        if ($key == null) {
            $messageArray = Array();
            foreach ($_SESSION['flashMessages'] as $key => $message) {
                $messageArray[] = $message;
                unset($_SESSION['flashMessages'][$key]);
            }
            if (sizeof($messageArray) == 0) {
                return null;
            } else {
                return $messageArray;
            }
        } else {
            if (isset($_SESSION['flashMessages'][$key])) {
                $temp = $_SESSION['flashMessages'][$key];
                unset($_SESSION['flashMessages'][$key]);
                return $temp;
            } else {
                return null;
            }
        }
    }

}
