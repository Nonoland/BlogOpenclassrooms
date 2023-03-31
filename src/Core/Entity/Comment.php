<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Entity;

class Comment extends ObjectModel
{

    public static array $definitions = [
        'table' => 'comment',
        'value' => [
            'title' => [],
            'body' => [],
            'valid' => [],
            'id_parent' => [],
            'id_post' => [],
            'id_user' => []
        ]
    ];

    protected string $title;
    protected string $body;
    protected bool $valid;
    protected int $idParent;
    protected int $idPost;
    protected int $idUser;

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
    public function getIdParent(): int
    {
        return $this->idParent;
    }

    /**
     * @param int $idParent
     */
    public function setIdParent(int $idParent): void
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
}
