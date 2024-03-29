<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Entity;

use DateTime;
use Nolandartois\BlogOpenclassrooms\Core\Database\Db;

class User extends ObjectModel
{
    public static array $definitions = [
        'table' => 'user',
        'values' => [
            'lastname' => [],
            'firstname' => [],
            'email' => [],
            'password' => [],
            'roles' => [],
            'expire_session' => ['required' => false],
            'forgotten_password' => ['required' => false],
            'active' => []
        ]
    ];

    public static array $userRoles = [
        'user',
        'admin'
    ];

    protected string $lastname = "";
    protected string $firstname = "";
    protected string $email = "";
    protected string $password = "";
    protected array $roles = [];
    protected ?DateTime $expireSession = null;
    protected bool $guest;

    protected ?string $forgottenPassword = null;

    protected int $active = 0;

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

    public  function getForgottenPassword(): String
    {
        return $this->forgottenPassword;
    }

    public  function setForgottenPassword(String $forgottenPassword): void
    {
        $this->forgottenPassword = $forgottenPassword;
    }

    public function active(): void
    {
        $this->active = 1;
    }

    public function desactive(): void
    {
        $this->active = 0;
    }

    public function getActive(): int
    {
        return $this->active;
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
}
