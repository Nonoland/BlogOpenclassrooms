<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers\Front;

use Nolandartois\BlogOpenclassrooms\Controllers\FrontController;
use Nolandartois\BlogOpenclassrooms\Core\Entity\Comment;
use Nolandartois\BlogOpenclassrooms\Core\Entity\Post;
use Nolandartois\BlogOpenclassrooms\Core\Entity\User;
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

    #[Route('POST', '/post/{slug:string}/comment')]
    public function postComment(array $params): Response
    {
        $post = Post::getPostBySlug($params['slug']);

        if (!$post) {
            self::redirect('/');
        }

        $request = $this->getRequest();

        $currentUser = (int)$request->getSession()->get('user', 0);
        $currentUser = new User($currentUser);

        if ($currentUser->isGuest()) {
            return self::redirect('/login');
        }

        if ($request->request->has('comment_title') && $request->request->has('comment_body')) {
            $comment = new Comment();
            $comment->setTitle($request->request->get('comment_title'));
            $comment->setBody($request->request->get('comment_body'));
            $comment->setIdUser($currentUser->getId());
            $comment->setIdPost($post->getId());
            $comment->setIdParent(null);

            if ($currentUser->hasRole('admin')) {
                $comment->setValid(true);
            }

            $comment->add();

            if ($request->request->has('comment-id')) {
                $comment->setIdParent((int)$request->request->get('comment-id'));
                $comment->update();
            }
        }

        return self::redirect('/post/'.$params['slug']);
    }
}
