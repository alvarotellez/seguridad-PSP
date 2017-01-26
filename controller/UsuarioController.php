<?php
/**
 * Created by PhpStorm.
 * User: atellez
 * Date: 26/01/17
 * Time: 10:10
 */

require_once "Controller.php";
require_once "Request.php";

class UsuarioController extends Controller
{
    public function manageGetVerb(Request $request)
    {

        $listaUsuarios = null;
        $id = null;
        $response = null;
        $code = null;


        if (isset($request->getUrlElements()[2])) {
            $id = $request->getUrlElements()[2];
        }


        $listaUsuarios = UsuarioHandlerModel::getUsuario($id);

        if ($listaUsuarios != null) {
            $code = '200';

        } else {

            if (UsuarioHandlerModel::isValid($id)) {
                $code = '404';
            } else {
                $code = '400';
            }

        }

        $response = new Response($code, null, $listaUsuarios, $request->getAccept());
        $response->generate();

    }


    public function managePostVerb(Request $request)
    {


        $usuario = null;
        $response = null;
        $code = null;
        $parameters = $request->getBodyParameters();


        $idUsuario = $parameters->idUsuario;
        $nomUsuario = $parameters->nomUsuario;
        $passUsuario = $parameters->passUsuario;




        $usuario = UsuarioHandlerModel::postUsuario($idUsuario, $nomUsuario, $passUsuario);
        if ($usuario != null) {
            $code = '200';

        } else {
            $code = '404';
        }


        $response = new Response($code, null, $usuario, $request->getAccept());
        $response->generate();
    }
}
