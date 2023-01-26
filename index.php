<?php

use Nolandartois\BlogOpenclassrooms\Controllers\IndexController;
use Nolandartois\BlogOpenclassrooms\Controllers\PostController;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Dispatcher;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Request;

require 'vendor/autoload.php';

$request = new Request();
$dispatcher = new Dispatcher();

$dispatcher->registerController(IndexController::class);
$dispatcher->registerController(PostController::class);

//dump($dispatcher->getRoutes());

$dispatcher->dispatch($request);
