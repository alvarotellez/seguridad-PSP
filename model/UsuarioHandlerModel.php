<?php
/**
 * Created by PhpStorm.
 * User: atellez
 * Date: 26/01/17
 * Time: 10:21
 */
require_once "ConsUsuariosModel.php";

class UsuarioHandlerModel
{
    public static function getUsuario($id)
    {
        $listaUsuarios = array();
        $usuario = null;
        $respuesta = null;

        $db = DatabaseModel::getInstance();

        $db_connection = $db->getConnection();

        $valid = self::isValid($id);

        if ($valid === true || $id == null) {
            $query = "SELECT " . \ConstantesDB\ConsUsuariosModel::IDUSUARIO . ","
                . \ConstantesDB\ConsUsuariosModel:: NOMUSUARIO. ","
                . \ConstantesDB\ConsUsuariosModel:: PASSUSUARIO. " FROM " . \ConstantesDB\ConsUsuariosModel::TABLE_NAME;


            if ($id != null) {
                $query = $query . " WHERE " . \ConstantesDB\ConsUsuariosModel::IDUSUARIO . " = ?";
            }

            $prep_query = $db_connection->prepare($query);


            if ($id != null) {
                $prep_query->bind_param('s', $id);
            }

            $prep_query->execute();



            $prep_query->bind_result($idUsario, $nomUsuario, $passUsuario);
            while ($prep_query->fetch()) {
                $nomUsuario = utf8_encode($nomUsuario);
                $passUsuario = utf8_encode($passUsuario);

                $usuario = new UsuarioModel($idUsario, $nomUsuario, $passUsuario);
                $listaUsuarios[] = $usuario;
            }
        }
        $db_connection->close();

        if(sizeof($listaUsuarios)<=1){
            $respuesta = $usuario;
        }else{
            $respuesta = $listaUsuarios;
        }

        return $respuesta;
    }

    //returns true if $id is a valid id for a book
    //In this case, it will be valid if it only contains
    //numeric characters, even if this $id does not exist in
    // the table of books
    public static function isValid($id)
    {
        $res = false;

        if (ctype_digit($id)) {
            $res = true;
        }
        return $res;
    }




//implementar esto!!!
    public static function postUsuario($idUsuario, $nomUsuario, $passUsuario){
        //creamos variable db que guardara una instancia de base de datos
        $db = DatabaseModel::getInstance();
        //creamos una variable conexion a la que le asignamos una conexion de mi db
        $db_connection = $db->getConnection();

        $query = "INSERT INTO " . \ConstantesDB\ConsUsuariosModel::TABLE_NAME ."("
            . \ConstantesDB\ConsUsuariosModel::IDUSUARIO . ","
            . \ConstantesDB\ConsUsuariosModel:: NOMUSUARIO. ","
            . \ConstantesDB\ConsUsuariosModel:: PASSUSUARIO. ") VALUES (?,?,?);";


        $prep_query = $db_connection->prepare($query);

        $prep_query->bind_param('iiisi', $idUsuario,$nomUsuario,$passUsuario);



        $prep_query->execute();
        //que quiero que me devuelve??

        // $prep_query->bind_result($idLib, $idTit, $ISBNcod,$Edit, $quantity);
//Esto no haria falta, es solo para ver que he insertado...
        $nomUsuario = utf8_encode($nomUsuario);
        $passUsuario = utf8_encode($passUsuario);
        $usuario = new UsuarioModel($idUsuario, $nomUsuario, $passUsuario);

        $db_connection->close();

        return $usuario;

    }
}