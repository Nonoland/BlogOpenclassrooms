<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Object;

use Nolandartois\BlogOpenclassrooms\Core\Database\Db;

class Post extends ObjectModel
{

    public static array $definitions = [
        'table' => 'post',
        'values' => [
            'title' => [],
            'description' => [],
            'body' => [],
            'views' => [],
            'id_user' => []
        ]
    ];

    protected string $title = "";
    protected string $description = "";
    protected string $body = "";
    protected int $views = 0;
    protected int $idUser = 0;

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
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return int
     */
    public function getViews(): int
    {
        return $this->views;
    }

    /**
     * @param int $views
     */
    public function setViews(int $views): void
    {
        $this->views = $views;
    }

    /**
     * @return int
     */
    public function getIdUser(): int
    {
        return $this->idUser;
    }

    /**
     * @param int $idUser
     */
    public function setIdUser(int $idUser): void
    {
        $this->idUser = $idUser;
    }

    public static function getAllPosts(): array
    {
        $dbInstance = Db::getInstance();
        return $dbInstance->select(self::$definitions['table'], '', [], 'date_add DESC');
    }
}
