<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers\Admin;

use Nolandartois\BlogOpenclassrooms\Controllers\AdminController;
use Nolandartois\BlogOpenclassrooms\Core\Entity\Post;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\Route;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\RouteAccess;

class AdminPostController extends AdminController
{

    #[Route('GET', '/admin/posts'), RouteAccess('admin')]
    public function indexPosts(): void
    {
        $posts = Post::getAllPosts();

        $template = $this->getTwig()->load('admin/posts/posts.twig');
        echo $template->render([
            'posts' => $posts
        ]);
    }

    #[Route(['GET'], '/admin/posts/new'), RouteAccess('admin')]
    public function postNew(): void
    {
        $template = $this->getTwig()->load('admin/posts/new.twig');
        echo $template->render();
    }

    #[Route(['POST'], '/admin/ajax/posts/new'), RouteAccess('admin')]
    public function postNewAjax(): void
    {
        $request = $this->getRequest();
        if (!$request->getIsset('post_title')
            || !$request->getIsset('post_description')
            || !$request->getIsset('post_body')) {
            $this->displayAjax(false);
        }

        $post = new Post();
        $post->setIdUser($request->getUser()->getId());
        $post->setDescription($request->getValuePost('post_description'));
        $post->setBody(
            json_decode(
                htmlspecialchars_decode($request->getValuePost('post_body'), ENT_QUOTES),
                true
            )
        );
        $post->setTitle($request->getValuePost('post_title'));
        $post->add();

        $this->displayAjax(true);
    }

    #[Route(['POST'], '/admin/ajax/posts/edit/{id_post:int}'), RouteAccess('admin')]
    public function postEditAjax(array $params): void
    {
        if (!$post = new Post($params['id_post'])) {
            $this->displayAjax(false);
        }

        $request = $this->getRequest();

        if ($request->getIsset('post_description') &&
            $request->getIsset('post_title') &&
            $request->getIsset('post_body')) {

            $post->setTitle($request->getValuePost('post_title'));
            $post->setDescription($request->getValuePost('post_description'));
            $post->setBody(
                json_decode(
                    htmlspecialchars_decode($request->getValuePost('post_body'), ENT_QUOTES),
                    true
                )
            );
            $post->update();

            $this->displayAjax(true);
        }

        $this->displayAjax(false);
    }

    #[Route(['GET', 'POST'], '/admin/posts/edit/{id_post:int}'), RouteAccess('admin')]
    public function postEdit(array $params): void
    {
        if (!$post = new Post($params['id_post'])) {
            self::redirect('/admin/posts');
        }

        $request = $this->getRequest();

        if ($request->getIsset('post_submit') &&
            $request->getIsset('post_description') &&
            $request->getIsset('post_title') &&
            $request->getIsset('post_body')) {

            $post->setTitle($request->getValuePost('post_title'));
            $post->setDescription($request->getValuePost('post_description'));
            $post->setBody($request->getValuePost('post_body'));
            $post->update();

            self::redirect('/admin/posts');
        }

        $template = $this->getTwig()->load('admin/posts/edit.twig');
        echo $template->render([
            'post' => $post
        ]);
    }

    #[Route(['GET'], '/admin/posts/delete/{id_post:int}'), RouteAccess('admin')]
    public function postDelete(array $params): void
    {
        $post = new Post((int)$params['id_post']);
        $post->delete();

        self::redirect('/admin/posts');
    }
}
