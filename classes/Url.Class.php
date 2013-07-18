<?php
/**
 * Author: CHEVALIER Alexis <alexis.chevalier@supinfo.com>
 * Date: 16/02/13
 * File: Url.class.php
 */


class Url
{
    /**
     * @var
     */
    private $_id;
    /**
     * @var
     */

    private $_short;
    /**
     * @var
     */

    private $_url_long;
    /**
     * @var
     */
    private $_user_id;
    /**
     * @var
     */
    private $_date_created;
    /**
     * @var
     */
    private $_enabled;
    /**
     * @var
     */
    private $_name;
    /**
     * @var
     */
    private $_clicked_count;


    function __construct($_date_created, $_id, $_url_long, $_user_id, $_enabled, $_name, $_clicked_count)
    {
        $this->_date_created = $_date_created;
        $this->_id = $_id;
        $this->_url_long = $_url_long;
        $this->_user_id = $_user_id;
        $this->_short = $this->base62_encode($_id);
        $this->_enabled = $_enabled;
        $this->_name = $_name;
        $this->_clicked_count = $_clicked_count;
    }

    /**
     * @param $date_created
     */
    public function setDateCreated($date_created)
    {
        $this->_date_created = $date_created;
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->_date_created;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param $url_long
     */
    public function setUrlLong($url_long)
    {
        $this->_url_long = $url_long;
    }

    /**
     * @return mixed
     */
    public function getUrlLong()
    {
        return $this->_url_long;
    }

    /**
     * @param $user_id
     */
    public function setUserId($user_id)
    {
        $this->_user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->_user_id;
    }

    /**
     * @return
     */
    public function getShort()
    {
        return $this->_short;
    }

    /**
     * @param  $enabled
     */
    public function setEnabled($enabled)
    {
        $this->_enabled = $enabled;
    }

    /**
     * @return
     */
    public function getEnabled()
    {
        return $this->_enabled;
    }

    /**
     * @param  $clicked_count
     */
    public function setClickedCount($clicked_count)
    {
        $this->_clicked_count = $clicked_count;
    }

    /**
     * @return
     */
    public function getClickedCount()
    {
        return $this->_clicked_count;
    }

    /**
     * @param  $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return
     */
    public function getName()
    {
        return $this->_name;
    }

    public function addOneClick()
    {
        $pdo = Db::get_pdo_instance();
        $sth = $pdo->prepare('UPDATE 2web_urls
                       SET clicked_count = clicked_count+1
                       WHERE id = :id');
        $sth->bindParam("id", $this->_id);
        $sth->execute();
    }


    //CRUD METHODS

    public static function delete($id, $userid)
    {
        if (!Urls_Model::checkUrlOwner($id, $userid, true)) {
            return false;
        } else {
            $pdo = Db::get_pdo_instance();
            $sth = $pdo->prepare('DELETE
                       FROM 2web_urls
                       WHERE id = :id');
            $sth->bindParam("id", $id);
            $sth->execute();

            $sth2 = $pdo->prepare('DELETE
                       FROM 2web_stats
                       WHERE id_url = :id');
            $sth2->bindParam("id", $id);
            $sth2->execute();
            return true;
        }
    }

    public static function update($id, $name, $userid)
    {
        if (!Urls_Model::checkUrlOwner($id, $userid, true)) {
            return false;
        } else {
            $pdo = Db::get_pdo_instance();
            $sth = $pdo->prepare('UPDATE 2web_urls
                       SET name = :name
                       WHERE id = :id');
            $sth->bindParam("id", $id);
            $sth->bindParam("name", $name);
            $sth->execute();
            return true;
        }
    }

    public static function enable($id, $userid)
    {
        if (!Urls_Model::checkUrlOwner($id, $userid, true)) {
            return false;
        } else {
            $pdo = Db::get_pdo_instance();
            $sth = $pdo->prepare('UPDATE 2web_urls
                       SET enabled = 1
                       WHERE id = :id');
            $sth->bindParam("id", $id);
            $sth->execute();
            return true;
        }
    }

    public static function disable($id, $userid)
    {
        if (!Urls_Model::checkUrlOwner($id, $userid, true)) {
            return false;
        } else {
            $pdo = Db::get_pdo_instance();
            $sth = $pdo->prepare('UPDATE 2web_urls
                       SET enabled = 0
                       WHERE id = :id');
            $sth->bindParam("id", $id);
            $sth->execute();
            return true;
        }
    }

    //URL Encoding
    public static function base62_encode($val)
    {
        return base_convert($val, 10, 36);
    }

    //URL Decoding
    public static function base62_decode($str)
    {
        return base_convert($str, 36, 10);
    }

}
