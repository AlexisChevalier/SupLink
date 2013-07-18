<?php
/**
 * Author: CHEVALIER Alexis <alexis.chevalier@supinfo.com>
 * Date: 16/02/13
 * File: User.class.php
 */

class User
{
    /**
     * @var
     */
    private $_id;
    /**
     * @var
     */
    private $_email;
    /**
     * @var
     */
    private $_password;
    /**
     * @var
     */
    private $_valid;

    /**
     * @param $email
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->_email;
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
     * @param $password
     */
    public function setPassword($password)
    {
        $this->_password = $password;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @param  $valid
     */
    public function setValid($valid)
    {
        $this->_valid = $valid;
    }

    /**
     * @return
     */
    public function getValid()
    {
        return $this->_valid;
    }


}
