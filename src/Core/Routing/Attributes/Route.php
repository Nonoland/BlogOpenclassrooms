<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes;

use Attribute;

#[Attribute]
class Route
{

    private const REGEX_INT = "[\d+]+";
    private const REGEX_STRING = "[a-zA-Z]+[a-zA-Z-0-9-_]+";
    private string $route;
    private string $routeRegex;
    private array $methodsHttp;
    private string $methodName;
    private string $routeName;
    private bool $mutable = false;
    private string $regexPattern = "/\{[a-zA-Z1-9--_]+\}/m";

    public function __construct(
        string|array $methodsHttp,
        string       $route,
        string       $methodName = '',
        string       $routeName = ''
    )
    {
        $this->route = $route;
        $this->routeRegex = '/^' . str_replace('/', '\/', $this->route) . '$/';

        $this->methodName = $methodName;

        if (is_string($methodsHttp)) {
            $this->methodsHttp[] = $methodsHttp;
        } else {
            $this->methodsHttp = $methodsHttp;
        }

        $this->loadRegex();

        if (empty($routeName)) {
            $routeName = $this->route;

            if ($routeName[0] == '/') {
                $routeName = substr($routeName, 1);
            }
            $routeName = str_replace('/', '_', $routeName);

            if (empty($routeName)) {
                $routeName = 'index';
            }

            $this->routeName = $routeName;
        }
    }

    private function loadRegex(): void
    {
        preg_match_all(
            $this->regexPattern,
            $this->route,
            $matches,
            PREG_SET_ORDER,
            0
        );

        if (empty($matches)) {
            return;
        }

        $this->mutable = true;

        foreach ($matches as $match) {
            $tmp = explode(':', substr($match[0], 1, strlen($match[0]) - 2));

            switch ($tmp[1]) {
                case "int":
                    $this->routeRegex = str_replace(
                        $match[0],
                        $this->getRegexGroup($tmp[0], self::REGEX_INT),
                        $this->routeRegex
                    );
                    break;
                case "string":
                    $this->routeRegex = str_replace(
                        $match[0],
                        $this->getRegexGroup($tmp[0],
                            self::REGEX_STRING),
                        $this->routeRegex
                    );
                    break;
                default:
                    throw new \InvalidArgumentException("Type $tmp[1] not exist for route regex");
            }
        }
    }

    private function getRegexGroup(string $name, string $pattern): string
    {
        return "(?P<$name>$pattern)";
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getRouteRegex(): string
    {
        return $this->routeRegex;
    }

    public function isMutable(): bool
    {
        return $this->mutable;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }

    public function setMethodName(string $methodName): void
    {
        $this->methodName = $methodName;
    }

    public function getMethodsHttp(): array
    {
        return $this->methodsHttp;
    }

    public function getRouteName(): string
    {
        return $this->routeName;
    }
}
