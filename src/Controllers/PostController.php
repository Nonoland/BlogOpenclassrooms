<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers;

use Nolandartois\BlogOpenclassrooms\Attributes\Route;

class PostController extends Controller
{

    #[Route('/articles/')]
    public function index()
    {
        echo 'articles';
    }

    #[Route('/article/{id}')]
    public function indexPostId(int $id)
    {

    }

    #[Route('/article/{slug}')]
    public function indexPostSlug(string $slug)
    {

    }

}