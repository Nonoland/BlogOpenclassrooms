<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers;

use JetBrains\PhpStorm\NoReturn;
use Nolandartois\BlogOpenclassrooms\Core\Database\Configuration;
use Nolandartois\BlogOpenclassrooms\Core\Object\Post;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Dispatcher;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Request;
use Nolandartois\BlogOpenclassrooms\Core\Twig\RouteExtension;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

abstract class Controller
{
    private Request $request;
    private Dispatcher $dispatcher;

    private $twigLoader;
    private $twig;

    public function __construct(Request $request, Dispatcher $dispatcher)
    {
        $this->request = $request;
        $this->dispatcher = $dispatcher;

        $this->twigLoader = new FilesystemLoader('public/templates');
        $this->twig = new Environment($this->twigLoader);

        $this->loadTwigVariables();
    }

    public function displayAjax(string $data): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo $data;
        exit();
    }

    protected function loadTwigVariables(): void
    {
        $this->twig->addGlobal('phpVersion', phpversion());
        $this->twig->addGlobal('blogName', Configuration::getConfiguration('blog_name'));
        $this->twig->addGlobal('twitterUrl', Configuration::getConfiguration('twitter_url'));
        $this->twig->addGlobal('githubUrl', Configuration::getConfiguration('github_url'));
        $this->twig->addGlobal('facebookUrl', Configuration::getConfiguration('facebook_url'));
        $this->twig->addGlobal('copyright', Configuration::getConfiguration('copyright'));

        $this->twig->addExtension(new RouteExtension($this->dispatcher));

        $this->twig->addFunction(new TwigFunction('getPostAuthorById', function(int $idUser) {
            return Post::getAuthorById($idUser);
        }));
    }

    protected function getRequest(): Request
    {
        return $this->request;
    }

    protected function getDispatcher(): Dispatcher
    {
        return $this->dispatcher;
    }

    protected function getTwigLoader(): FilesystemLoader
    {
        return $this->twigLoader;
    }

    protected function getTwig(): Environment
    {
        return $this->twig;
    }

    public static function redirect(string $route): void
    {
        header("Location: $route");
        exit();
    }
}
