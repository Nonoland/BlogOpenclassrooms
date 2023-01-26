<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers;

use Nolandartois\BlogOpenclassrooms\Core\Routing\Route;

class PostController extends Controller
{

    #[Route('GET', '/posts')]
    public function index(): void
    {
        echo 'posts';
    }

    #[Route('GET', '/post/{id:int}')]
    public function postId(array $params): void
    {
        echo 'post id';
        dump($params);
    }

    #[Route('GET', '/post/{slug:string}')]
    public function postSlug(array $params): void
    {
        echo 'post slug';
        dump($params);
    }

}
