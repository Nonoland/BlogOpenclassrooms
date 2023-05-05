<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Entity;

use Nolandartois\BlogOpenclassrooms\Core\Database\Db;

class Contact extends ObjectModel
{
    public static array $definitions = [
        'table' => 'contact',
        'values' => [
            'title' => [],
            'message' => [],
            'email' => [],
        ]
    ];

    protected string $title = "";
    protected string $email = "";
    protected string $message = "";

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
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
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
