<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alexischevalier
 * Date: 22/02/13
 * Time: 15:18
 * To change this template use File | Settings | File Templates.
 */
class Stat
{
    private $_id;
    private $_timestamp;
    private $_id_url;
    private $_referer;
    private $_ip_address;
    private $_country_code;

    function __construct($_id_url, $_ip_address, $_referer, $_country_code = null, $_timestamp = null, $_id = null)
    {
        $this->_id = $_id;
        $this->_id_url = $_id_url;
        $this->_ip_address = $_ip_address;
        $this->_referer = $_referer;

        if ($_country_code == null) {
            $json = file_get_contents('http://api.ipinfodb.com/v3/ip-country/?key=ed3ede46627cfa62b5694dace7a131ff6d7e62e0caee628eb1200e44bed60268&ip=' . $_ip_address . '&format=json');
            $json = json_decode($json, true);
            if (isset($json['countryCode']) && $json['countryCode'] != '-' && !empty($json['countryCode'])) {
                $this->_country_code = strtoupper($json['countryCode']);
            } else {
                $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
                preg_match_all('/(\W|^)([a-z]{2})([^a-z]|$)/six', $lang, $country, PREG_PATTERN_ORDER);
                $this->_country_code = strtoupper($country[2][0]);
            }
        }
        $this->_timestamp = null;
    }

    public function setCountryCode($country_code)
    {
        $this->_country_code = $country_code;
    }

    public function getCountryCode()
    {
        return $this->_country_code;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setIdUrl($id_url)
    {
        $this->_id_url = $id_url;
    }

    public function getIdUrl()
    {
        return $this->_id_url;
    }

    public function setIpAddress($ip_address)
    {
        $this->_ip_address = $ip_address;
    }

    public function getIpAddress()
    {
        return $this->_ip_address;
    }

    public function setReferer($referer)
    {
        $this->_referer = $referer;
    }

    public function getReferer()
    {
        return $this->_referer;
    }

    public function setTimestamp($timestamp)
    {
        $this->_timestamp = $timestamp;
    }

    public function getTimestamp()
    {
        return $this->_timestamp;
    }

    //CRUD METHODS

    public function insert()
    {
        $pdo = Db::get_pdo_instance();
        $sth2 = $pdo->prepare('INSERT INTO 2web_stats VALUES(null, NOW(), :id_url, :referer, :ip_adress, :country_code)');
        $sth2->bindParam("id_url", $this->_id_url);
        $sth2->bindParam("referer", $this->_referer);
        $sth2->bindParam("ip_adress", $this->_ip_address);
        $sth2->bindParam("country_code", $this->_country_code);
        $sth2->execute();
        return $pdo->lastInsertId();
    }
}
