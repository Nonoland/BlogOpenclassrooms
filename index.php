<?php

use Nolandartois\BlogOpenclassrooms\Controllers\IndexController;
use Nolandartois\BlogOpenclassrooms\Controllers\PostController;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Dispatcher;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Request;

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$request = new Request();
$dispatcher = new Dispatcher();

$dispatcher->registerController(IndexController::class);
$dispatcher->registerController(PostController::class);

$dispatcher->dispatch($request);
