<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Auth;

use Cassandra\Date;
use DateTime;
use Nolandartois\BlogOpenclassrooms\Core\Database\Configuration;
use Nolandartois\BlogOpenclassrooms\Core\Database\Db;
use Nolandartois\BlogOpenclassrooms\Core\Entity\User;
use Nolandartois\BlogOpenclassrooms\Core\Mail\Mail;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\HttpFoundation\Request;

class Authentification
{
    public static function connectUser(string $email, string $password): false|User
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

        $currentUser = new User($users[0]['id']);

        if (!$currentUser->getActive()) {
            return false;
        }

        $expireSession = new DateTime();
        $expireSession->modify('+1 day');

        $currentUser->setExpireSession($expireSession);
        $currentUser->update();

        return $currentUser;
    }

    public static function logoutUser(int $idUser): bool
    {
        $expireSession = new DateTime();

        $user = new User($idUser);
        if ($user->isGuest()) {
            return false;
        }

        $user->setExpireSession($expireSession);
        return $user->update();
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

    public static function generateForgottenPasswordKey(): String
    {
        $date = new DateTime();
        $date->modify('+' . $_ENV['FORGOTTEN_PASSWORD_DELAY'] . ' days');

        $expireationCode = base_convert($date->getTimestamp(), 10, 36);

        return self::generateRandomKey() . $expireationCode;
    }

    public static function registerNewUser(string $firstname, string $lastname, string $email, string $password): bool
    {
        if (User::userExistByEmail($email)) {
            return false;
        }

        $user = new User();
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setRoles(['user']);
        $user->active();

        return $user->add();
    }

    public static function updateSession(Request $request): void
    {
        $currentUser = (int)$request->getSession()->get('user', 0);
        $currentUser = new User($currentUser);
        if ($currentUser->isGuest()) {
            return;
        }

        $dateNow = new DateTime();

        if ($currentUser->getExpireSession()->diff($dateNow)->invert === 0) {
            $request->getSession()->set('user', 0);
        }
    }

    public static function forgottenPassword(string $email): void
    {
        $dbInstance = Db::getInstance();
        $findUser = $dbInstance->select('user', sprintf('email = %s', $dbInstance->getPDO()->quote($email)), ['id']);

        if (empty($findUser)) {
            return;
        }

        $user = new User($findUser[0]['id']);
        $user->setForgottenPassword(self::generateForgottenPasswordKey());
        $user->desactive();
        $user->update();

        $link = Configuration::getConfiguration("blog_domain") . '/change_password/' . $user->getForgottenPassword();

        Mail::sendMailToUser(
            (int) $findUser[0]['id'],
            "Mot de passe oublié",
            "Vous avez indiqué avoir oublié votre mot de passe. Voici le lien pour modifier votre mot de passe <a href=\"$link\">Accèder à la page</a>",
            "Vous avez indiqué avoir oublié votre mot de passe. Voici le lien pour modifier votre mot de passe : $link",
            true
        );
    }

    public static function isForgottenPasswordKeyValid(string $keyWithExpiration): bool
    {
        $key = substr($keyWithExpiration, 0, 32);
        $expirationCode = substr($keyWithExpiration, 32);

        $expirationTimestamp = base_convert($expirationCode, 36, 10);

        $dbIntance = Db::getInstance();
        $findUser = $dbIntance->select('user', sprintf('forgotten_password = "%s"', $keyWithExpiration), ['id', 'active']);

        if (empty($findUser)) {
            return false;
        }

        if ($findUser[0]['active'] == 1) {
            return false;
        }

        if (new DateTime() > (new DateTime())->setTimestamp($expirationTimestamp)) {
            return false;
        }

        return true;
    }

    public static function changePasswordWithForgottenPasswordKey(string $key, string $password): bool
    {
        if (!self::isForgottenPasswordKeyValid($key)) {
            return false;
        }

        $dbIntance = Db::getInstance();
        $findUser = $dbIntance->select('user', sprintf('forgotten_password = "%s"', $key), ['id', 'active']);

        if (empty($findUser)) {
            return false;
        }

        $user = new User($findUser[0]['id']);
        $user->active();
        $user->setPassword($password);
        $user->setForgottenPassword('');
        return $user->update();
    }

}
