<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Auth;

use Nolandartois\BlogOpenclassrooms\Core\Database\Db;
use Nolandartois\BlogOpenclassrooms\Core\Object\User;

class Authentification
{
    public static function connectUser(string $email, string $password): false|string
    {
        $dbInstance = Db::getInstance();

        $users = $dbInstance->select(User::$definitions['table'], "email = \"$email\"");

        if (count($users) > 1) {
            return false;
        }

        if (empty($users)) {
            return false;
        }

        if (!password_verify($password, $users[0]['password'])) {
            return false;
        }

        $cookieKey = self::generateRandomKey();

        $dbInstance->update(
            User::$definitions['table'],
            [
                'cookie_key' => $cookieKey
            ],
            "email = \"$email\""
        );

        return $cookieKey;
    }

    public static function getAuthentificateUser(string $cookieKey): User|false
    {
        $dbInstance = Db::getInstance();

        $users = $dbInstance->select(User::$definitions['table'], "cookie_key = \"$cookieKey\"");

        if (count($users) > 1) {
            return false;
        }

        if (empty($users)) {
            return false;
        }

        return new User($users[0]['id']);
    }

    public static function generateRandomKey(): string
    {
        return bin2hex(random_bytes(16));
    }

}
