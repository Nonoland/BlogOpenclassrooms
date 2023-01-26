<?php

use Nolandartois\BlogOpenclassrooms\Controllers\IndexController;
use Nolandartois\BlogOpenclassrooms\Controllers\PostController;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Dispatcher;

require 'vendor/autoload.php';

$dispatcher = new Dispatcher();

$dispatcher->registerController(IndexController::class);
$dispatcher->registerController(PostController::class);

$dispatcher->dispatch();
