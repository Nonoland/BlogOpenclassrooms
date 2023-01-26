<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers;

use Nolandartois\BlogOpenclassrooms\Core\Routing\Route;

class PostController extends Controller
{

    #[Route('GET', '/posts')]
    public function index()
    {
        echo 'posts';
    }

    #[Route('GET', '/post/{id:int}')]
    public function postId($params)
    {
        echo 'post id';
    }

    #[Route('GET', '/post/{slug:string}')]
    public function postSlug($params)
    {
        echo 'post slug';
    }

}