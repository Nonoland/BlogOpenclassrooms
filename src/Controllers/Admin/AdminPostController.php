<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers\Admin;

use Nolandartois\BlogOpenclassrooms\Controllers\AdminController;
use Nolandartois\BlogOpenclassrooms\Core\Object\Post;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Route;

class AdminPostController extends AdminController
{


    #[Route('GET', '/admin/posts')]
    public function indexPosts(): void
    {
        $posts = Post::getAllPosts();

        $template = $this->getTwig()->load('admin/posts/posts.twig');
        echo $template->render([
            'posts' => $posts
        ]);
    }

    #[Route(['GET', 'POST'], '/admin/posts/new')]
    public function postNew(): void
    {
        $request = $this->getRequest();

        if ($request->getIsset('post_submit') &&
            $request->getIsset('post_description') &&
            $request->getIsset('post_title') &&
            $request->getIsset('post_body')) {

            $post = new Post();
            $post->setTitle($request->getValuePost('post_title'));
            $post->setDescription($request->getValuePost('post_description'));
            $post->setBody($request->getValuePost('post_body'));
            $post->setIdUser(1);
            $post->add();

            $this->redirect("/admin/posts");
        }

        $template = $this->getTwig()->load('admin/posts/new.twig');
        echo $template->render();
    }

    #[Route(['GET', 'POST'], '/admin/posts/edit/{id_post:int}')]
    public function postEdit(array $params): void
    {
        if (!$post = new Post($params['id_post'])) {
            $this->redirect('/admin/posts');
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

            $this->redirect('/admin/posts');
        }

        $template = $this->getTwig()->load('admin/posts/edit.twig');
        echo $template->render([
            'post' => $post
        ]);
    }

    #[Route(['GET'], '/admin/posts/delete/{id_post:int}')]
    public function postDelete(array $params): void
    {
        $post = new Post((int)$params['id_post']);
        $post->delete();

        $this->redirect('/admin/posts');
    }

}
