<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Routing;

use Exception;
use InvalidArgumentException;
use Nolandartois\BlogOpenclassrooms\Controllers\Controller;
use Nolandartois\BlogOpenclassrooms\Core\Entity\User;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\Route;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\RouteAccess;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Dispatcher
{
    private array $routes = [];

    private Request $request;
    private Response $response;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function dispatch(): void
    {
        foreach ($this->routes as $controller => $routes) {
            /** @var Route $route */
            foreach ($routes as $route) {
                if (!in_array($this->request->getMethod(), $route->getMethodsHttp())) {
                    continue;
                }

                $result = preg_match(
                    $route->getRouteRegex(),
                    $this->request->query->get('path'),
                    $matches
                );

                if (!$result) {
                    continue;
                }

                $matches = array_filter($matches, "is_string", ARRAY_FILTER_USE_KEY);

                try {
                    $this->response = $this->executeRoute($controller, $route, $matches);
                } catch (Exception $e) {

                    switch($e->getCode()) {
                        case 401: $this->response = Controller::redirect("/"); break;
                        case 404: $this->response = Controller::redirect("/404"); break;
                        default: $this->response = Controller::redirect("/500"); break;
                    }

                    if ($_ENV['MODE'] == 'DEV') {
                        $content = $e->getMessage();
                        $content .= "<br />";
                        $content .= $e->getTraceAsString();

                        $this->response = new Response($content, 500);
                    }

                    $this->response->prepare($this->request)->send();
                }

                $this->response->prepare($this->request)->send();

                return;
            }
        }

        header('HTTP/1.0 404 Not Found');
    }

    /**
     * @throws ReflectionException
     */
    private function executeRoute(string $controller, Route $route, array $params = []): Response
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
            $currentUser = (int)$this->request->getSession()->get('user', 0);
            $currentUser = new User($currentUser);
            if (!array_intersect($routeAccess->getRoles(), $currentUser->getRoles())) {
                throw new Exception("You are not authorised to access this page!", 401);
            }
        }

        if ($route->isMutable()) {
            return $controller->$methodName($params);
        }

        return $controller->$methodName();
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
