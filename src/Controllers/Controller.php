<?php
namespace Nolandartois\BlogOpenclassrooms\Controllers;

use Nolandartois\BlogOpenclassrooms\Core\Database\Configuration;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class Controller{
    private Request $request;

    private $twigLoader;
    private $twig;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->twigLoader = new FilesystemLoader('public/templates');
        $this->twig = new Environment($this->twigLoader);

        $this->loadTwigVariables();
    }

    protected function getRequest(): Request
    {
        return $this->request;
    }

    protected function getTwigLoader(): FilesystemLoader
    {
        return $this->twigLoader;
    }

    protected function getTwig(): Environment
    {
        return $this->twig;
    }

    protected function loadTwigVariables()
    {
        $this->twig->addGlobal('phpVersion', phpversion());
        $this->twig->addGlobal('blogName', Configuration::getConfiguration('blog_name'));
    }
}
