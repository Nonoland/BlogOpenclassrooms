<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers;

use Nolandartois\BlogOpenclassrooms\Core\Routing\Route;

class PostController extends Controller
{

    #[Route('GET', '/posts')]
    public function index(): void
    {
    }

    #[Route('GET', '/post/{id:int}')]
    public function postId(array $params): void
    {
    }

    #[Route('GET', '/post/{slug:string}')]
    public function postSlug(array $params): void
    {
    }

}
