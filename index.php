<?php

use Nolandartois\BlogOpenclassrooms\Attributes\Target;
use Nolandartois\BlogOpenclassrooms\Controllers\IndexController;
use Nolandartois\BlogOpenclassrooms\Controllers\PostController;

require 'vendor/autoload.php';

if (!isset($_GET['path'])) {
    header404();
}

$path = $_GET['path'];

$controllers = [
    IndexController::class,
    PostController::class
];

foreach ($controllers as $controller) {
    $reflector = new ReflectionClass($controller);
    $methodsController = $reflector->getMethods();

    foreach ($methodsController as $method) {
        if (ReflectionMethod::IS_PUBLIC != $method->getModifiers()) {
            continue;
        }

        $attributes = $method->getAttributes(Target::class);
        if (empty($attributes)) {
            continue;
        }

        if ($attributes[0]->getArguments()[0] == $path || (strlen($path) == 0 && $attributes[0]->getArguments()[0] == '/')) {
            executeController($controller, $method->getName());
        }
    }
}

header404();

function executeController($controllerSource, $method)
{
    $controller = new $controllerSource();
    $controller->$method();
    exit();
}

function header404()
{
    header('HTTP/1.0 404 Not Found');
    exit();
}
