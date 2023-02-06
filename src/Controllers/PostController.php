<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers;

use Nolandartois\BlogOpenclassrooms\Core\Routing\Route;

class PostController extends Controller
{
    #[Route('GET', '/post/{slug:string}')]
    public function post(array $params): void
    {
        echo 'test';
    }
}
