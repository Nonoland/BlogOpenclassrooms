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
        $template = $this->getTwig()->load('admin/posts/posts.twig');
        echo $template->render();
    }

    #[Route(['GET', 'POST'], '/admin/posts/new')]
    public function postNew()
    {
        $request = $this->getRequest();

        if ($request->getIsset('post_submit') &&
            $request->getIsset('post_title') &&
            $request->getIsset('post_body')) {
            $post = new Post();
            $post->setTitle($request->getValuePost('post_title'));
            $post->setBody($request->getValuePost('post_body'));
            $post->setIdUser(1);
            $post->add();
        }

        $template = $this->getTwig()->load('admin/posts/new.twig');
        echo $template->render();
    }
}
