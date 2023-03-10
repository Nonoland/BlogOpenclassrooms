<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers;

use Nolandartois\BlogOpenclassrooms\Core\Database\Configuration;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Dispatcher;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Request;
use Nolandartois\BlogOpenclassrooms\Core\Twig\RouteExtension;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

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

    protected function loadTwigVariables()
    {
        $this->twig->addGlobal('phpVersion', phpversion());
        $this->twig->addGlobal('blogName', Configuration::getConfiguration('blog_name'));

        $this->twig->addExtension(new RouteExtension($this->dispatcher));
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

    protected function redirect($route)
    {
        header("Location: $route");
        exit();
    }
}
