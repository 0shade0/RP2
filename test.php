<?php

$dir = __DIR__;

function platformSlashes($path){
    return str_replace('/', DIRECTORY_SEPARATOR, $path);
}

require_once platformSlashes($dir . '/app/database/db.class.php');
$db = DB::getConnection();
if(session_id() == '') session_start();

$title = 'Naslov nije zadan';

if(!isset($_GET['rt']))
{
    $controller = 'test';
    $action = 'index';
}
else
{
    $parts = explode('/', $_GET['rt']);

    if(isset($parts[0]) && preg_match('/^[A-Za-z0-9]+$/', $parts[0]))
        $controller = $parts[0];
    else
        $controller = 'test';

    if(isset($parts[1]) && preg_match('/^[A-Za-z0-9]+$/', $parts[1]))
        $action = $parts[1];
    else
        $action = 'index';
}

$controllerName = $controller . 'Controller';

require_once platformSlashes($dir . '/controller/' . $controllerName . '.php');
$con = new $controllerName();
$con->$action();

?>