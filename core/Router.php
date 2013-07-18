<?php
/**
 * Author: CHEVALIER Alexis <alexis.chevalier@supinfo.com>
 * Date: 04/02/13
 * File: router.php
 */

require_once(SERVER_ROOT . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'routes.php');


/**
 * Permet de charger automatiquement les classes
 */
function __autoload($className)
{
    if (preg_match('/^(.+)_Model/', $className)) {
        list($filename, $suffix) = explode('_', $className);

        $file = SERVER_ROOT . '/models/' . strtolower($filename) . '.php';
    } else {
        $file = SERVER_ROOT . '/classes/' . ucfirst(strtolower($className)) . '.Class.php';
    }


    if (file_exists($file)) {
        include_once($file);
    } else {
        die("File '$filename' containing class '$className' not found.");
    }
}


spl_autoload_register('__autoload');

//Recuperation des arguments
if(isset($_SERVER['REDIRECT_URL'])){
    $request = $_SERVER['REDIRECT_URL'];
}else{
    $request = $_SERVER['REQUEST_URI'];
}


//Suppression de la partie inutile de l'url
$limit = 1;
$request = str_replace(BEFORE_ARGUMENTS, '', $request, $limit);
//Récupére les arguments
$parsed = explode('/', $request);
unset($parsed[0]);

$route_found = false;
$attr_names = Array();
$pattern = Array();
$args = Array();

/* ROUTER V2 */
//Fetch des routes
foreach ($routes as $route => $route_options) {
    $route_valid = true;
    //Explosion de la route
    $route_exploded = explode('/', $route);
    //Si la taille des routes correspond
    if (sizeof($route_exploded) == sizeof($parsed)) {
        //Fetch des morceaux de la route
        for ($a = 0; $a < sizeof($route_exploded); $a++) {
            $url_element = $route_exploded[$a];
            //Si c'est un attribut
            if (preg_match("/^{(.+)}/", $url_element)) {
                $pattern[$a] = '{argument}';
                $attr_names[$a] = str_replace(Array('{', '}'), '', $url_element);
            } else { //si c'est un morceau de route fixe
                $pattern[$a] = $url_element;
            }
        }

        //Fetch de la requête
        for ($a = 0; $a < sizeof($pattern); $a++) {
            if (preg_match("/^{(.+)}/", $pattern[$a])) {
                $args[$attr_names[$a]] = $parsed[$a + 1];
            } else if ($pattern[$a] != $parsed[$a + 1]) {
                $route_valid = false;
                break;
            }
        }
        if ($route_valid == true) {
            $controller = $route_options['controller'];
            $method = $route_options['method'];
            $route_found = true;
            break;
        }
    }
}

if ($route_found == false) {
    include_once(SERVER_ROOT . '/controllers/' . DEFAULT_CONTROLLER . '.php');
    $controller = DEFAULT_CONTROLLER;
    $method = "_404";
    $args = Array('error' => '404 not found');

    header("Status: 404 ERROR", false, 404);
} else {
    $target = SERVER_ROOT . '/controllers/' . $controller . '.php';
    require_once $target;
}


$controller = ucfirst($controller) . '_Controller';
$controller = new $controller;

$controller->$method($args);


