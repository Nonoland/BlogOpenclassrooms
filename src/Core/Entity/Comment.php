<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Entity;

use Nolandartois\BlogOpenclassrooms\Core\Database\Db;

class Comment extends ObjectModel
{

    public static array $definitions = [
        'table' => 'comment',
        'values' => [
            'title' => [],
            'body' => [],
            'valid' => [],
            'id_parent' => [],
            'id_post' => [],
            'id_user' => []
        ]
    ];

    protected string $title = "";
    protected string $body = "";
    protected bool $valid = false;
    protected ?int $idParent = null;
    protected ?int $idPost = null;
    protected ?int $idUser = null;

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
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * @param bool $valid
     */
    public function setValid(bool $valid): void
    {
        $this->valid = $valid;
    }

    /**
     * @return int
     */
    public function getIdParent(): int|null
    {
        return $this->idParent;
    }

    /**
     * @param int $idParent
     */
    public function setIdParent(?int $idParent): void
    {
        $this->idParent = $idParent;
    }

    /**
     * @return int
     */
    public function getIdPost(): int
    {
        return $this->idPost;
    }

    /**
     * @param int $idPost
     */
    public function setIdPost(int $idPost): void
    {
        $this->idPost = $idPost;
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

    public function getChildren(): array
    {
        $dbInstance = Db::getInstance();
        $comments = $dbInstance->select(
            "comment",
            "id_parent = $this->id",
            ['id'],
            'date_add ASC'
        );

        if (empty($comments)) {
            return [];
        }

        $result = [];

        foreach ($comments as $comment) {
            $result[] = new Comment($comment['id']);
        }

        return $result;
    }

    public function getParentComment(): Comment|bool
    {
        if ($this->idParent == null) {
            return false;
        }

        return new Comment($this->idParent);
    }

    public static function getCommentsByIdPost(int $idPost, bool $deep = false): array
    {
        $dbInstance = Db::getInstance();
        $comments = $dbInstance->select(
            "comment",
            "id_post = $idPost" . ($deep ?: " AND id_parent IS NULL"),
            ['id'],
            'date_add ASC'
        );

        if (empty($comments)) {
            return [];
        }

        $result = [];

        foreach ($comments as $comment) {
            $result[] = new Comment($comment['id']);
        }

        return $result;
    }

    public static function getAllComments(): array
    {
        $dbInstance = Db::getInstance();
        $result = $dbInstance->select(self::$definitions['table'], '', [], 'date_add DESC');

        foreach ($result as &$row) {
            $row = new Comment($row['id']);
        }

        return $result;
    }
}
