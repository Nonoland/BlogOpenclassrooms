<?php

use Nolandartois\BlogOpenclassrooms\Controllers\IndexController;
use Nolandartois\BlogOpenclassrooms\Controllers\PostController;
use Nolandartois\BlogOpenclassrooms\Dispatcher;

require 'vendor/autoload.php';

$dispatcher = new Dispatcher();

try {
    $dispatcher->registerController(IndexController::class);
    $dispatcher->registerController(PostController::class);
} catch (ReflectionException|Exception $e) {
    dump($e);
}

$dispatcher->dispatch();
