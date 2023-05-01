<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Entity;

use DateTime;
use Nolandartois\BlogOpenclassrooms\Core\Database\Db;

class Post extends ObjectModel
{

    public static array $definitions = [
        'table' => 'post',
        'values' => [
            'title' => [],
            'slug' => [],
            'description' => [],
            'body' => [],
            'views' => [],
            'id_user' => []
        ]
    ];

    protected string $title = "";
    protected string $slug = "";
    protected string $description = "";
    protected array $body = [];
    protected int $views = 0;
    protected int $idUser = 0;
    protected array $comments = [];

    public function __construct(int $id = 0)
    {
        parent::__construct($id);

        //Load comments
        if ($this->id != 0) {
            $this->comments = Comment::getCommentsByIdPost($this->id);
        }
    }

    public function add(): bool
    {
        $this->slug = str_replace(" ", "_", $this->title);
        parent::add();
        $this->slug = $this->id . '_' . $this->slug;
        return $this->update();
    }

    public static function getAllPosts(): array
    {
        $dbInstance = Db::getInstance();
        $result = $dbInstance->select(self::$definitions['table'], '', [], 'date_add DESC');

        foreach ($result as &$row) {
            $row = new Post($row['id']);
        }

        return $result;
    }

    public static function getPostBySlug(string $slug): bool|Post
    {
        $dbInstance = Db::getInstance();
        $result = $dbInstance->select(self::$definitions['table'], "slug LIKE '%$slug%'", [], '', 1);

        if (empty($result)) {
            return false;
        }

        return new Post((int)$result[0]['id']);
    }

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
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(array $body): void
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

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getComments(): array
    {
        return $this->comments;
    }

    public static function getAuthorById(int $idUser): string
    {
        $user = new User($idUser);
        if ($user->isGuest()) {
            return "Unknown";
        }

        return $user->getFirstname().' '.$user->getLastname();
    }
}
