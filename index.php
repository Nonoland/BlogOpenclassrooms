<?php

use Nolandartois\BlogOpenclassrooms\Controllers\Admin\AdminCommentController;
use Nolandartois\BlogOpenclassrooms\Controllers\Admin\AdminPostController;
use Nolandartois\BlogOpenclassrooms\Controllers\Admin\AdminSettingsController;
use Nolandartois\BlogOpenclassrooms\Controllers\Admin\AdminUserController;
use Nolandartois\BlogOpenclassrooms\Controllers\Admin\DashboardController;
use Nolandartois\BlogOpenclassrooms\Controllers\Front\AuthController;
use Nolandartois\BlogOpenclassrooms\Controllers\Front\IndexController;
use Nolandartois\BlogOpenclassrooms\Controllers\Front\PostController;
use Nolandartois\BlogOpenclassrooms\Core\Auth\Authentification;
use Nolandartois\BlogOpenclassrooms\Core\Entity\User;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Dispatcher;
use ScssPhp\ScssPhp\Compiler;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

require_once __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

/* SCSS */
if ($_ENV['MODE'] == 'DEV') {
    $compilerSCSS = new Compiler();
    $resultFront = $compilerSCSS->compileString('@import "'.$_ENV['BLOG_PATH'].'public/assets/front/css/main.scss";');

    file_put_contents($_ENV['BLOG_PATH'].'public/assets/front/css/main.css', $resultFront->getCss());
}

/* Index */
$request = Request::createFromGlobals();

//Create Session
if (!$request->hasSession()) {
    $storage = new NativeSessionStorage([
        'cookie_secure' => 'auto',
        'cookie_samesite' => Cookie::SAMESITE_LAX,
    ]);
    $session = new Session($storage);
    $session->start();
    $request->setSession($session);
}

Authentification::updateSession($request);

$dispatcher = new Dispatcher($request);

try {
    $dispatcher->registerController(IndexController::class);
    $dispatcher->registerController(AuthController::class);
    $dispatcher->registerController(PostController::class);
    $dispatcher->registerController(DashboardController::class);
    $dispatcher->registerController(AdminPostController::class);
    $dispatcher->registerController(AdminCommentController::class);
    $dispatcher->registerController(AdminUserController::class);
    $dispatcher->registerController(AdminSettingsController::class);
} catch(Exception $e) {
    echo $e->getMessage();
}

$dispatcher->dispatch();
