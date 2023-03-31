<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Twig;

use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\Route;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Dispatcher;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RouteExtension extends AbstractExtension
{
    private Dispatcher $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('route', [$this, 'getRoute'])
        ];
    }

    public function getRoute($routeName)
    {
        foreach ($this->dispatcher->getRoutes() as $controller) {
            /** @var Route $route */
            foreach ($controller as $route) {
                if ($route->getRouteName() == $routeName) {
                    return $route->getRoute();
                }
            }
        }

        return "/";
    }
}
