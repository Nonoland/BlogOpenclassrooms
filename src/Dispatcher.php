<?php
namespace Nolandartois\BlogOpenclassrooms;

use Exception;
use JetBrains\PhpStorm\NoReturn;
use Nolandartois\BlogOpenclassrooms\Attributes\Route;
use Nolandartois\BlogOpenclassrooms\Controllers\Controller;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class Dispatcher
{
    private array $controllers = [];

    private array $routes = [];

    private string $currentPath;

    public function __construct()
    {
        $this->currentPath = $_GET['path'];
    }

    #[NoReturn] public function dispatch(): void
    {
        if (!array_key_exists($this->currentPath, $this->routes)) {
            header('HTTP/1.0 404 Not Found');
            exit();
        }

        $this->executeRoute($this->currentPath);
    }

    #[NoReturn] private function executeRoute($route): void
    {
        $route = $this->routes[$route];

        $controller = $route['controller'];
        $action = $route['action'];

        $controller = new $controller();
        $controller->$action();
        exit();
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function registerController(string $controller): void
    {
        if (!is_subclass_of($controller, Controller::class)) {
            throw new Exception("$controller is not a Controller");
        }

        $this->controllers[] = $controller;

        $reflector = new ReflectionClass($controller);
        $methodsController = $reflector->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methodsController as $method) {
            $attributes = $method->getAttributes(Route::class);
            if (empty($attributes)) {
                continue;
            }

            $attribute = $attributes[0];
            $route = $attribute->getArguments()[0];

            $this->routes[$route] = [
                'controller' => $reflector->name,
                'action' => $method->getName()
            ];
        }
    }
}