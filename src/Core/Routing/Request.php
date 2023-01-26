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
}
