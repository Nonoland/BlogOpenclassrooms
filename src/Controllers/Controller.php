<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers;

use Nolandartois\BlogOpenclassrooms\Core\Database\Configuration;
use Nolandartois\BlogOpenclassrooms\Core\Entity\Post;
use Nolandartois\BlogOpenclassrooms\Core\Entity\User;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Dispatcher;
use Nolandartois\BlogOpenclassrooms\Core\Twig\RouteExtension;
use Nolandartois\BlogOpenclassrooms\Core\Twig\WPMExtension;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        $this->twig = new Environment($this->twigLoader, [
            'debug' => true
        ]);

        $this->loadTwigVariables();
    }

    public function displayAjax(string $data): Response
    {
        return new Response(
            $data,
            200,
            ['Content-Type: application/json; charset=utf-8']
        );
    }

    protected function loadTwigVariables(): void
    {
        $this->twig->addGlobal('phpVersion', phpversion());
        $this->twig->addGlobal('blogName', Configuration::getConfiguration('blog_name'));
        $this->twig->addGlobal('twitterUrl', Configuration::getConfiguration('twitter_url'));
        $this->twig->addGlobal('githubUrl', Configuration::getConfiguration('github_url'));
        $this->twig->addGlobal('facebookUrl', Configuration::getConfiguration('facebook_url'));
        $this->twig->addGlobal('copyright', Configuration::getConfiguration('copyright'));
        $this->twig->addGlobal('IMAGE_POST_PATH', $_ENV['IMAGE_POST_PATH']);
        $this->twig->addGlobal('userRoles', User::$userRoles);

        $this->twig->addExtension(new RouteExtension($this->dispatcher));
        $this->twig->addExtension(new WPMExtension());
        $this->twig->addExtension(new \Twig\Extension\DebugExtension());

        $this->twig->addFunction(new TwigFunction('getPostAuthorById', function(int $idUser) {
            return Post::getAuthorById($idUser);
        }));

        /* Load Current User */
        $currentUser = (int)$this->request->getSession()->get('user', 0);
        $currentUser = new User($currentUser);
        $this->twig->addGlobal('currentUser', $currentUser);
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

    public static function redirect(string $route): Response
    {
        return new RedirectResponse($route);
    }
}
