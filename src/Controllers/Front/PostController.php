<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers\Front;

use Nolandartois\BlogOpenclassrooms\Controllers\FrontController;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Route;

class PostController extends FrontController
{
    #[Route('GET', '/post/{slug:string}')]
    public function post(): void
    {
        echo 'test';
    }
}
