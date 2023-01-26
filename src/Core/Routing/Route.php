<?php
namespace Nolandartois\BlogOpenclassrooms\Core\Routing;

use Attribute;

#[Attribute]
class Route {

    private string $route;
    private string $routeRegex;

    private string $methodName;

    private bool $mutable = false;

    private string $regexPattern = "/\{[a-zA-Z1-9--_]+\}/m";

    private const REGEX_INT = "[\d+]+";
    private const REGEX_STRING = "[a-zA-Z]+[a-zA-Z-0-9-_]+";

    public function __construct(string $route, string $methodName) {
        $this->route = $route;
        $this->routeRegex = '/^'.str_replace('/', '\/', $this->route).'$/';

        $this->methodName = $methodName;

        $this->loadRegex();
    }

    private function loadRegex(): void
    {
        $regexResult = preg_match_all($this->regexPattern, $this->route, $matches, PREG_SET_ORDER, 0);
        if (empty($matches)) {
            return;
        }

        $this->mutable = true;

        foreach ($matches as $match) {
            $tmp = explode(':', substr($match[0], 1, strlen($match[0])-2));

            switch ($tmp[1]) {
                case "int":
                    $this->routeRegex = str_replace($match[0], $this->getRegexGroup($tmp[0], self::REGEX_INT), $this->routeRegex);
                    break;
                case "string":
                    $this->routeRegex = str_replace($match[0], $this->getRegexGroup($tmp[0], self::REGEX_STRING), $this->routeRegex);
                    break;
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
}