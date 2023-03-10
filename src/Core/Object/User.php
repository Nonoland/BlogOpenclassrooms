<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Object;

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
            'roles' => []
        ]
    ];

    protected string $username = "";
    protected string $lastname = "";
    protected string $firstname = "";
    protected string $email = "";
    protected string $password = "";
    protected array $roles = [];

    public static function getAllUsers()
    {
        return Db::getInstance()->select(self::$definitions['table']);
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
}
