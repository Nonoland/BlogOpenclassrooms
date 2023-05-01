<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers\Admin;

use Nolandartois\BlogOpenclassrooms\Controllers\AdminController;
use Nolandartois\BlogOpenclassrooms\Core\Entity\Comment;
use Nolandartois\BlogOpenclassrooms\Core\Entity\User;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\Route;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\RouteAccess;
use Symfony\Component\HttpFoundation\Response;

class AdminCommentController extends AdminController
{

    #[Route('GET', '/admin/comments'), RouteAccess('admin')]
    public function indexPosts(): Response
    {
        $comments = Comment::getAllComments();

        $template = $this->getTwig()->load('admin/comments/comments.twig');
        $content = $template->render([
            'comments' => $comments
        ]);

        return new Response($content);
    }

    #[Route(['GET', 'POST'], '/admin/comments/edit/{id_comment:int}'), RouteAccess('admin')]
    public function editComment(array $params): Response
    {
        $request = $this->getRequest();
        $comment = new Comment($params['id_comment']);

        $commentSubmit = $request->request->has('comment_submit');
        $commentTitle = $request->request->get('comment_title', false);
        $commentValid = $request->request->get('comment_valid', false);
        $commentBody = $request->request->get('comment_body', false);

        if ($commentSubmit && $commentTitle && $commentBody) {
            $comment->setTitle($commentTitle);
            $comment->setBody($commentBody);
            $comment->setValid($commentValid);

            $comment->update();

            return self::redirect('/admin/comments');
        }

        $template = $this->getTwig()->load('admin/comments/edit.twig');
        $content = $template->render([
            'comment' => $comment
        ]);

        return new Response($content);
    }

    #[Route(['GET'], '/admin/comments/delete/{id_comment:int}'), RouteAccess('admin')]
    public function deleteComment(array $params): Response
    {
        $comment = new Comment($params['id_comment']);
        $comment->delete();

        return self::redirect('/admin/comments');
    }
}
