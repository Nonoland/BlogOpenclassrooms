<?php
namespace Nolandartois\BlogOpenclassrooms\Core\Routing;

use Exception;
use JetBrains\PhpStorm\NoReturn;
use Nolandartois\BlogOpenclassrooms\Controllers\Controller;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class Dispatcher
{
    private array $controllers = [];

    private array $routes = [];

    #[NoReturn] public function dispatch(Request $request): void
    {
        foreach ($this->routes as $controller => $routes) {
            /** @var Route $route */
            foreach ($routes as $route) {
                if (!in_array($request->getMethodHttp(), $route->getMethodsHttp())) {
                    continue;
                }

                $result = preg_match(
                    $route->getRouteRegex(),
                    $request->getCurrentRoute(),
                    $matches
                );

                if (!$result) {
                    continue;
                }

                $matches = array_filter($matches, "is_string", ARRAY_FILTER_USE_KEY);

                $this->executeRoute($controller, $route, $matches);
            }
        }

        header('HTTP/1.0 404 Not Found');
        exit();
    }

    #[NoReturn] private function executeRoute(string $controller, Route $route, $params = []): void
    {
        $controller = new $controller();
        $methodName = $route->getMethodName();

        if ($route->isMutable()) {
            $controller->$methodName($params);
        } else {
            $controller->$methodName();
        }

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
            $route = new Route($attribute->getArguments()[0], $attribute->getArguments()[1], $method->getName());

            $this->routes[$controller][] = $route;
        }
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}