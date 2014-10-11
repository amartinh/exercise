<?php
/*
 * Project setup
 */
ini_set('display_errors', 1);
$configs = parse_ini_file('Config/config.ini', true);
DEFINE('CONTROLLERSPATH', 'Controllers/');
DEFINE('MODELSPATH', 'Models/');
DEFINE('SETUPPATH', 'Setup/');


/**
 * Parses requests according to URI and config file
 * Calls loadController function to load proper controllers
 * @param void
 * @return void
 */


function dispatch(){
    global $configs;

    $request = array_filter(explode("/", $_SERVER['REQUEST_URI']));
    $controllerReq = $request[1];

    if (in_array($controllerReq, array_keys($configs)) && $controllerReq == "api"){
        $version = $request[2];
        $action = $request[3];
        if (isset($request[4])){
            $param = $request[4];
        } else {
            $param = null;
        }
        try{
            loadController($controllerReq, $action, $version, $param);
        } catch (Exception $e) {
            header("HTTP/1.0 404 Not Found");
            echo "<h1>Not found</h1>";
        }
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "<h1>Not found</h1>";
    }
}

/**
 * Loads controllers files according to request
 * @param $controller
 * @param $action
 * @param null $version
 * @param null $param
 * @throws Exception
 */
function loadController($controller, $action, $version = null, $param = null) {
    if (isset($version) && $version == 'v1'){
        $class = $controller . 'Controller';
        include CONTROLLERSPATH . $controller . '/' . $version. '/' . $class . '.php';
        $resource = new $class();
        if(method_exists(get_class($resource), $action)){
            $resource -> $action($param);
        } else {
            throw new Exception("Method not found");
        }
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "<h1>Not found</h1>";
    }
}
//All the fun begins..
dispatch();


