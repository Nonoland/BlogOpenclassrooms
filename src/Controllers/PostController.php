<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers;

use Nolandartois\BlogOpenclassrooms\Attributes\Target;

class PostController extends Controller
{

    #[Target('articles/')]
    public function index()
    {
        echo 'articles';
    }

    #[Target('article/{id}')]
    public function indexPost(int $id)
    {

    }

}