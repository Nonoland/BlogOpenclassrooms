<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Object;

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

    public function __construct(int $id = 0)
    {
        parent::__construct($id);
    }

    public static function getAllPosts(): array
    {
        $dbInstance = Db::getInstance();
        return $dbInstance->select(self::$definitions['table'], '', [], 'date_add DESC');
    }

    public static function getPostById(int $idPost)
    {
        $dbInstance = Db::getInstance();
        $result = $dbInstance->select(self::$definitions['table'], "id = $idPost", [], '', 1);

        if (empty($result)) {
            return false;
        }

        $post = new Post();
        $post->id = (int)$result[0]['id'];
        $post->slug = $result[0]['slug'];
        $post->title = $result[0]['title'];
        $post->description = $result[0]['description'];
        $post->body = json_decode(htmlspecialchars_decode($result[0]['body']), true);
        $post->dateAdd = DateTime::createFromFormat(self::DATE_FORMAT, $result[0]['date_add']);
        $post->dateUpd = DateTime::createFromFormat(self::DATE_FORMAT, $result[0]['date_upd']);
        $post->views = (int)$result[0]['views'];
        $post->idUser = (int)$result[0]['id_user'];

        return $post;
    }

    public static function getPostBySlug(string $slug): bool|Post
    {
        $dbInstance = Db::getInstance();
        $result = $dbInstance->select(self::$definitions['table'], "slug LIKE '%$slug%'", [], '', 1);

        if (empty($result)) {
            return false;
        }

        $post = new Post();
        $post->id = (int)$result[0]['id'];
        $post->slug = $result[0]['slug'];
        $post->title = $result[0]['title'];
        $post->description = $result[0]['description'];
        $post->body = json_decode(htmlspecialchars_decode($result[0]['body']), true);
        $post->dateAdd = DateTime::createFromFormat(self::DATE_FORMAT, $result[0]['date_add']);
        $post->dateUpd = DateTime::createFromFormat(self::DATE_FORMAT, $result[0]['date_upd']);
        $post->views = (int)$result[0]['views'];
        $post->idUser = (int)$result[0]['id_user'];

        return $post;
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
}
