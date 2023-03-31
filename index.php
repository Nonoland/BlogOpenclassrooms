<?php

use Nolandartois\BlogOpenclassrooms\Controllers\Admin\AdminPostController;
use Nolandartois\BlogOpenclassrooms\Controllers\Admin\AdminSettingsController;
use Nolandartois\BlogOpenclassrooms\Controllers\Admin\AdminUserController;
use Nolandartois\BlogOpenclassrooms\Controllers\Admin\DashboardController;
use Nolandartois\BlogOpenclassrooms\Controllers\Admin\DevelopmentController;
use Nolandartois\BlogOpenclassrooms\Controllers\Front\AuthController;
use Nolandartois\BlogOpenclassrooms\Controllers\Front\IndexController;
use Nolandartois\BlogOpenclassrooms\Controllers\Front\PostController;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Dispatcher;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Request;

use ScssPhp\ScssPhp\Compiler;

require_once __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

/* SCSS */
if ($_ENV['MODE'] == 'DEV') {
    $compilerSCSS = new Compiler();
    $resultFront = $compilerSCSS->compileString('@import "'.$_ENV['PATH'].'public/assets/css/front/main.scss";');
    $resultAdmin = $compilerSCSS->compileString('@import "'.$_ENV['PATH'].'public/assets/css/admin/main.scss";');

    file_put_contents($_ENV['PATH'].'public/assets/css/front/main.css', $resultFront->getCss());
    file_put_contents($_ENV['PATH'].'public/assets/css/admin/main.css', $resultAdmin->getCss());
}

/* Index */

$request = new Request();
$dispatcher = new Dispatcher();

$dispatcher->registerController(IndexController::class);
$dispatcher->registerController(AuthController::class);
$dispatcher->registerController(PostController::class);
$dispatcher->registerController(DashboardController::class);
$dispatcher->registerController(AdminPostController::class);
$dispatcher->registerController(AdminUserController::class);
$dispatcher->registerController(AdminSettingsController::class);

$dispatcher->dispatch($request);
