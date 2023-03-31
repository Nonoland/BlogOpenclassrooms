<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Routing;

use Exception;
use InvalidArgumentException;
use Nolandartois\BlogOpenclassrooms\Controllers\Controller;
use Nolandartois\BlogOpenclassrooms\Core\Config\Config;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\Route;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\RouteAccess;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class Dispatcher
{
    private array $routes = [];
    private Request $request;

    public function dispatch(Request $request): void
    {
        $this->request = $request;

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

                try {
                    $this->executeRoute($controller, $route, $matches);
                } catch (Exception $e) {

                    if ($_ENV['MODE'] == 'DEV') {
                        echo $e->getMessage();
                        echo $e->getTraceAsString();

                        return;
                    }

                    switch($e->getCode()) {
                        case 401: Controller::redirect("/"); break;
                        case 404: Controller::redirect("/404"); break;
                        default: Controller::redirect("/500"); break;
                    }
                }

                return;
            }
        }

        header('HTTP/1.0 404 Not Found');
    }

    /**
     * @throws ReflectionException
     */
    private function executeRoute(string $controller, Route $route, array $params = []): void
    {
        $controller = new $controller($this->request, $this);
        $methodName = $route->getMethodName();

        $reflector = new ReflectionClass($controller);
        $methodData = $reflector->getMethod($methodName);

        if (!empty($routeAccess = $methodData->getAttributes(RouteAccess::class))) {
            if (count($routeAccess) > 1) {
                throw new Exception("Too many RouteAccess attributes", 500);
            }

            /** @var RouteAccess $routeAccess */
            $routeAccess = $routeAccess[0]->newInstance();
            if (!array_intersect($routeAccess->getRoles(), $this->request->getUser()->getRoles())) {
                throw new Exception("You are not authorised to access this page!", 401);
            }
        }

        if ($route->isMutable()) {
            $controller->$methodName($params);
        } else {
            $controller->$methodName();
        }
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function registerController(string $controller): void
    {
        if (!is_subclass_of($controller, Controller::class)) {
            throw new InvalidArgumentException("$controller is not a Controller");
        }

        $reflector = new ReflectionClass($controller);
        $methodsController = $reflector->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methodsController as $method) {
            $attributes = $method->getAttributes(Route::class);
            if (empty($attributes)) {
                continue;
            }

            /** @var Route $route */
            $route = $attributes[0]->newInstance();
            $route->setMethodName($method->getName());

            $this->routes[$controller][] = $route;
        }
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
