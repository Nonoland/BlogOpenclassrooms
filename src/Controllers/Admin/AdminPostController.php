<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers\Admin;

use Nolandartois\BlogOpenclassrooms\Controllers\AdminController;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Route;
use Nolandartois\BlogOpenclassrooms\Object\Post;

class AdminPostController extends AdminController
{
    #[Route('GET', '/admin/posts')]
    public function indexPosts(): void
    {
        $template = $this->getTwig()->load('admin/posts/posts.twig');
        echo $template->render();
    }

    #[Route(['GET', 'POST'], '/admin/posts/new')]
    public function postNew()
    {
        if (isset($_POST['post_submit']) &&
            isset($_POST['post_title']) &&
            isset($_POST['post_body'])) {
            $post = new Post();
            $post->setTitle($_POST['post_title']);
            $post->setBody($_POST['post_body']);
            $post->setIdUser(1);
            $post->add();
        }

        $template = $this->getTwig()->load('admin/posts/new.twig');
        echo $template->render();
    }
}
