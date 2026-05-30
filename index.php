<?php
require_once 'config/config.php';
require_once 'config/database.php';

// Simple routing
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Load controller
$controllerFile = "controllers/" . ucfirst($controller) . "Controller.php";

if(file_exists($controllerFile)) {
    require_once $controllerFile;
    $controllerClass = ucfirst($controller) . "Controller";
    $controllerObj = new $controllerClass();
    
    if(method_exists($controllerObj, $action)) {
        if(isset($_GET['id'])) {
            $controllerObj->$action($_GET['id']);
        } else {
            $controllerObj->$action();
        }
    } else {
        die("Action not found");
    }
} else {
    die("Controller not found");
}
?>