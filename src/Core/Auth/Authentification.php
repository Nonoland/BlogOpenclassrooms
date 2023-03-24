<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Auth;

use Nolandartois\BlogOpenclassrooms\Core\Database\Db;
use Nolandartois\BlogOpenclassrooms\Core\Object\User;

class Authentification
{
    public static function connectUser(string $email, string $password)
    {
        $dbInstance = Db::getInstance();

        $users = $dbInstance->select(User::$definitions['table'], "email = \"$email\"");

        if (count($users) > 1) {
            throw new \Exception("Trop d'utilisateur avec le même email !");
        }

        if (empty($users)) {
            throw new \Exception("Pas d'utilisateur trouvé !");
        }

        if (!password_verify($password, $users[0]['password'])) {
            throw new \Exception("Mot de passe incorrect");
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

    public static function getAuthentificateUser(string $cookieKey): User
    {
        $dbInstance = Db::getInstance();

        $users = $dbInstance->select(User::$definitions['table'], "cookie_key = \"$cookieKey\"");

        if (count($users) > 1) {
            throw new \Exception("Trop d'utilisateur avec le même cookieKey !");
        }

        if (empty($users)) {
            throw new \Exception("Pas d'utilisateur trouvé");
        }

        return new User($users[0]['id']);
    }

    public static function generateRandomKey()
    {
        return bin2hex(random_bytes(16));
    }

}
