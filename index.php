<?php
/**
 * Created by PhpStorm.
 * User: atellez
 * Date: 26/01/17
 * Time: 10:54
 */
require_once "Request.php";
require_once "Response.php";

//Autoload rules
spl_autoload_register('apiAutoload');//Ejecuto automaticamente la funcion apiAutoload ?? si
function apiAutoload($classname) //$classname se reconoce automaticamente
{
    $res = false;
    //Esto es para cargar las clases del proyecto que vaya a utilizar con require_once
    //If the class name ends in "Controller", then try to locate the class in the controller directory to include it (require_once)
    if (preg_match('/[a-zA-Z]+Controller$/', $classname)) {
        //[a-zA-Z]+ el mas significa uno o mas caracteres en esos rangos a-z y A-Z
        //$ en una expresion regular (regexp) significa fin de la cadena
        //__DIR__ D:/xampp/htdocs/MiBiblioteca?? + /controller/+ LibroController? +.php
        //el punto encadena
        if (file_exists(__DIR__ . '/controller/' . $classname . '.php')) {
//            echo "cargamos clase: " . __DIR__ . '/controller/' . $classname . '.php';
            require_once __DIR__ . '/controller/' . $classname . '.php';
            $res = true;
        }
    } elseif (preg_match('/[a-zA-Z]+Model$/', $classname)) {
        if (file_exists(__DIR__ . '/model/' . $classname . '.php')) {
//            echo "<br/>cargamos clase: " . __DIR__ . '/model/' . $classname . '.php';
            require_once __DIR__ . '/model/' . $classname . '.php';
//            echo "clase cargada.......................";
            $res = true;
        }
    }
    //Instead of having Views, like in a Model-View-Controller project,
    //we will have a Response class. So we don't need the following.
    //Although we could have different classes to generate the output,
    //for example: JsonView, XmlView, HtmlView... I think in our case
    //it will be better to have asingle class to generate the output (Response class)
    //elseif (preg_match('/[a-zA-Z]+View$/', $classname)) {
    //    require_once __DIR__ . '/views/' . $classname . '.php';
    //    $res = true;
    //}
    return $res;
}


//Let's retrieve all the information from the request
//$_SERVER  son Variables set by the web server or otherwise directly related to the execution environment of the current script.
$verb = $_SERVER['REQUEST_METHOD'];
//IMPORTANT: WITH CGI OR FASTCGI, PATH_INFO WILL NOT BE AVAILABLE!!!
//SO WE NEED FPM OR PHP AS APACHE MODULE (UNSECURE, DEPRECATED) INSTEAD OF CGI OR FASTCGI
// si no esta vacio ?=coge esto       :=si no, si no esta vacio  ?=coge esto       :=si no
$path_info = !empty($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : (!empty($_SERVER['ORIG_PATH_INFO']) ? $_SERVER['ORIG_PATH_INFO'] : '');
$url_elements = explode('/', $path_info); //Devuelve un array de strings
//$url_elements = explode('/', $_SERVER['PATH_INFO']);
$query_string = null;
if (isset($_SERVER['QUERY_STRING'])) {
    parse_str($_SERVER['QUERY_STRING'], $query_string);
}
$body = file_get_contents("php://input");//Reads entire file into a string
//The function returns the read data or false on failure.
if ($body === false) {
    $body = null;
}
$content_type = null;
if (isset($_SERVER['CONTENT_TYPE'])) {
    $content_type = $_SERVER['CONTENT_TYPE'];
}
$accept = null;
if (isset($_SERVER['HTTP_ACCEPT'])) {
    $accept = $_SERVER['HTTP_ACCEPT'];
}

//Creo un objeto Request con los datos obtenidos de la peticion
$req = new Request($verb, $url_elements, $query_string, $body, $content_type, $accept);


// route the request to the right place
//ucfirst() pone la primera letra mayusculas
$controller_name = ucfirst($url_elements[1]) . 'Controller'; //biblioteca.dev(0)/libro(1)/1(2)??
//es a partir de htdocs: MiBiblioteca(0)/LibroController(1)
//$controller_name = 'LibroController';
if (class_exists($controller_name)) {
    $controller = new $controller_name(); //Creo un nuevo LibroController (x ej)
    $action_name = 'manage' . ucfirst(strtolower($verb)) . 'Verb';  // creo cadena: 'manageGetVerb'
    $controller->$action_name($req); //Llamo al metodo manageGetVerb(Obj Request) del controlador LibroController
    //$result = $controller->$action_name($req);
    //print_r($result);
} //If class does not exist, we will send the request to NotFoundController
else {
    $controller = new NotFoundController();
    $controller->manage($req); //We don't care about the HTTP verb
}