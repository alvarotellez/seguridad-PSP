<?php
/**
 * Created by PhpStorm.
 * User: atellez
 * Date: 26/01/17
 * Time: 10:24
 */
class UsuarioModel implements JsonSerializable
{
    private $idUser;
    private $userName;
    private $password;


    public function __construct($idUsuario,$nomUsuario,$passUsuario)
    {
        $this->idUser=$idUsuario;
        $this->userName=$nomUsuario;
        $this->password=$passUsuario;
    }



    function jsonSerialize()
    {
        return array(
            'idUser' => $this->idUser,
            'userName' => $this->userName,
            'password' => $this->password,
        );
    }

    public function __sleep(){
        return array(
            'idUser',
            'userName',
            'password',
            );
    }

    /**
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param mixed $idUser
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param mixed $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
}