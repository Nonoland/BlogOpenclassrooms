<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers\Front;

use Nolandartois\BlogOpenclassrooms\Controllers\FrontController;
use Nolandartois\BlogOpenclassrooms\Core\Object\Post;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Route;

class PostController extends FrontController
{
    #[Route('GET', '/post/{slug:string}')]
    public function showPostSlug(array $params): void
    {
        $post = Post::getPostBySlug($params['slug']);

        if (!$post) {
            $this->redirect('/');
        }

        $templates = $this->getTwig()->load('front/post/post.twig');
        echo $templates->render([
            'post' => $post
        ]);
    }

    #[Route('GET', '/post/{id_post:int}')]
    public function showPostId(array $params): void
    {
        $post = Post::getPostById($params['id_post']);

        if (!$post) {
            $this->redirect('/');
        }

        $templates = $this->getTwig()->load('front/post/post.twig');
        echo $templates->render([
            'post' => $post
        ]);
    }
}
