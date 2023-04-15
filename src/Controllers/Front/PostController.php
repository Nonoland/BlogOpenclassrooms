<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers\Front;

use Nolandartois\BlogOpenclassrooms\Controllers\FrontController;
use Nolandartois\BlogOpenclassrooms\Core\Entity\Post;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\Route;
use Symfony\Component\HttpFoundation\Response;

class PostController extends FrontController
{
    #[Route('GET', '/post/{slug:string}')]
    public function showPostSlug(array $params): Response
    {
        $post = Post::getPostBySlug($params['slug']);

        if (!$post) {
            self::redirect('/');
        }

        $templates = $this->getTwig()->load('front/post/post.twig');
        $content = $templates->render([
            'post' => $post
        ]);

        return new Response($content);
    }

    #[Route('GET', '/post/{id_post:int}')]
    public function showPostId(array $params): Response
    {
        $post = Post::getPostById($params['id_post']);

        if (!$post) {
            self::redirect('/');
        }

        $templates = $this->getTwig()->load('front/post/post.twig');
        $content = $templates->render([
            'post' => $post
        ]);

        return new Response($content);
    }
}
