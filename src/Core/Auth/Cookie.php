<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Auth;

class Cookie
{
    private $name;
    private $value;

    const COOKIE_DURATION = 86400;

    public function __construct($name)
    {
        $this->name = $name;
        $this->value = [];

        if (isset($_COOKIE[$this->name])) {
            $this->value = json_decode($_COOKIE[$this->name], true);
        }
    }

    public function setValue(string $key, mixed $value): void
    {
        $this->value[$key] = $value;
    }

    public function getValue($key): string
    {
        if (array_key_exists($key, $this->value)) {
            return $this->value[$key];
        }

        return "";
    }

    public function setAuthentificationCookieKey(string $cookieKey): void
    {
        $this->value['cookieKey'] = $cookieKey;
    }

    public function getAuthentificationCookieKey(): mixed
    {
        return $this->getValue('cookieKey');
    }

    public function writeCookie(): bool
    {
        return setcookie($this->name, json_encode($this->value), time() + self::COOKIE_DURATION, '/', $_ENV['DOMAIN'], true, false);
    }

    public function clearCookie(): void
    {
        $this->value = [];
        $this->writeCookie();
    }

    public static function getInstance(): Cookie
    {
        return new Cookie('blog');
    }

}
