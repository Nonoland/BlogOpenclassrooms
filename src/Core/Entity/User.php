<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Entity;

use DateTime;
use Nolandartois\BlogOpenclassrooms\Core\Database\Db;

class User extends ObjectModel
{
    public static array $definitions = [
        'table' => 'user',
        'values' => [
            'username' => [],
            'lastname' => [],
            'firstname' => [],
            'email' => [],
            'password' => [],
            'roles' => [],
            'expire_session' => ['required' => false]
        ]
    ];

    public static array $userRoles = [
        'user',
        'admin'
    ];

    protected string $username = "";
    protected string $lastname = "";
    protected string $firstname = "";
    protected string $email = "";
    protected string $password = "";
    protected array $roles = [];
    protected ?DateTime $expireSession = null;
    protected bool $guest;

    public function __construct(int $id = 0)
    {
        parent::__construct($id);

        $this->guest = false;

        if ($this->id == 0) {
            $this->guest = true;
        }
    }

    public function add(): bool
    {
        if ($addResult = parent::add()) {
            $this->id == 0 ? $this->guest = true : $this->guest = false;
        }

        return $addResult;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function addRoles(string $name): void
    {
        $this->roles[] = $name;
    }

    public function hasRole(string $name): bool
    {
        return in_array($name, $this->roles);
    }

    public function setExpireSession(DateTime $dateExpire): void
    {
        $this->expireSession = $dateExpire;
    }

    public function getExpireSession(): DateTime
    {
        return $this->expireSession;
    }

    public function isGuest(): bool
    {
        return $this->guest;
    }

    public static function userExistByEmail(string $email): bool
    {
        $dbInstance = Db::getInstance();
        $result = $dbInstance->select(self::$definitions['table'], "email LIKE '%$email%'", [], '', 1);

        return !empty($result);
    }

    public static function getAllUsers(): false|array
    {
        return Db::getInstance()->select(self::$definitions['table']);
    }

    public static function getCookieKey(int $idUser): string|bool
    {
        $dbInstance = Db::getInstance();
        $result = $dbInstance->select(
            self::$definitions['table'],
            "id = $idUser"
        );

        if (empty($result)) {
            return false;
        }

        return $result[0]['cookie_key'];
    }
}
