<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Routing;

use Nolandartois\BlogOpenclassrooms\Core\Auth\Authentification;
use Nolandartois\BlogOpenclassrooms\Core\Auth\Cookie;
use Nolandartois\BlogOpenclassrooms\Core\Object\User;

class Request
{
    private string $currentRoute;
    private string $methodHttp;
    private Cookie $cookie;
    private User $user;

    public function __construct()
    {
        $this->currentRoute = $_GET['path'];
        $this->methodHttp = ucwords($_SERVER['REQUEST_METHOD']);
        $this->cookie = Cookie::getInstance();
        $this->loadUser();
    }

    protected function loadUser(): void
    {
        if (!$userFound = Authentification::getAuthentificateUser($this->cookie->getAuthentificationCookieKey())) {
            $this->cookie->clearCookie();
            $this->user = new User();

            return;
        }

        $this->user = $userFound;
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

    public function sanitizeData(string $value): string
    {
        if (!$value) {
            return $value;
        }

        $value = trim($value);
        $value = htmlspecialchars($value);

        return $value;
    }

    public function getValueGet(string $name): string
    {
        return $this->sanitizeData($_GET[$name]);
    }

    public function getCookie(): Cookie
    {
        return $this->cookie;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
