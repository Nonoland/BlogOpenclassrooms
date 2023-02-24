<?php

use Nolandartois\BlogOpenclassrooms\Controllers\Admin\DashboardController;
use Nolandartois\BlogOpenclassrooms\Controllers\Front\IndexController;
use Nolandartois\BlogOpenclassrooms\Controllers\Front\PostController;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Dispatcher;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Request;

require_once __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$request = new Request();
$dispatcher = new Dispatcher();

$dispatcher->registerController(IndexController::class);
$dispatcher->registerController(PostController::class);
$dispatcher->registerController(DashboardController::class);

$dispatcher->dispatch($request);
