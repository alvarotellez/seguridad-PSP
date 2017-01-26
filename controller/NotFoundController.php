<?php
/**
 * Created by PhpStorm.
 * User: atellez
 * Date: 26/01/17
 * Time: 10:17
 */
require_once "Controller.php";

class NotFoundController extends Controller
{
    public function manage(Request $req)
    {
        $response = new Response('404', null, null, $req->getAccept());
        $response->generate();
    }
}