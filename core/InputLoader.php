<?php
/**
 * Author: CHEVALIER Alexis <alexis.chevalier@supinfo.com>
 * Date: 04/02/13
 * File: InputLoader.php
 */


InputLoader::setGet($_GET);
InputLoader::setPost($_POST);

/**
 *
 */


class InputLoader
{
    /**
     * $_GET Value
     * @var
     */
    private static $_get;
    /**
     * $_POST Value
     * @var
     */
    private static $_post;

    /**
     * Return the Get Value
     * @return mixed
     */
    public static function Get($key = null)
    {
        if ($key != null) {
            if (isset(self::$_get[$key]) && !empty(self::$_get[$key])) {
                return self::$_get[$key];
            } else {
                return null;
            }
        } else {
            return self::$_get;
        }
    }


    /**
     * Return the Post Value
     * @return mixed
     */
    public static function Post($key = null)
    {
        if ($key != null) {
            if (isset(self::$_post[$key]) && !empty(self::$_post[$key])) {
                return self::$_post[$key];
            } else {
                return null;
            }
        } else {
            return self::$_post;
        }
    }


    /**
     * @param  $get
     */
    public static function setGet($get)
    {
        self::$_get = $get;
    }

    /**
     * @param  $post
     */
    public static function setPost($post)
    {
        self::$_post = $post;
    }
}
