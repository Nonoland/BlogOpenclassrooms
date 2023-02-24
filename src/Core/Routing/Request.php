<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Routing;

class Request
{
    private string $currentRoute;
    private string $methodHttp;

    public function __construct()
    {
        $this->currentRoute = $_GET['path'];
        $this->methodHttp = ucwords($_SERVER['REQUEST_METHOD']);
    }

    public function getCurrentRoute(): string
    {
        return $this->currentRoute;
    }

    public function getMethodHttp(): string
    {
        return $this->methodHttp;
    }

    public function getIsset(string $key): bool
    {
        return isset($_POST[$key]) || isset($_GET[$key]);
    }

    public function getValuePost(string $name): string
    {
        return $this->sanitizeData($_POST[$name]);
    }

    public function getValueGet(string $name): string
    {
        return $this->sanitizeData($_GET[$name]);
    }

    public function sanitizeData(string $value): string
    {
        $value = trim($value);
        $value = htmlspecialchars($value);

        return $value;
    }
}
