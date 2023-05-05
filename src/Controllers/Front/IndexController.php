<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers\Front;

use Nolandartois\BlogOpenclassrooms\Controllers\FrontController;
use Nolandartois\BlogOpenclassrooms\Core\Entity\Contact;
use Nolandartois\BlogOpenclassrooms\Core\Entity\Post;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\Route;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends FrontController
{
    #[Route('GET', '/')]
    public function index(): Response
    {
        $posts = Post::getAllPosts();

        $templates = $this->getTwig()->load('front/home/home.twig');
        $content =  $templates->render([
            'posts' => $posts
        ]);

        return new Response($content);
    }

    #[Route(['GET', 'POST'], '/contact')]
    public function contact(): Response
    {
        $request = $this->getRequest()->request;
        $a = ['email', 'message', 'subject'];

        if (array_intersect($a, array_keys($request->all())) == $a) {
            $contact = new Contact();
            $contact->setEmail($request->get('email'));
            $contact->setTitle($request->get('subject'));
            $contact->setMessage($request->get('message'));
            $contact->add();
        }

        $templates = $this->getTwig()->load('front/user/contact.twig');
        $content = $templates->render([]);

        return new Response($content);
    }

    #[Route('GET', '/404')]
    public function page404(): Response
    {
        return new Response('404', 404);
    }

    #[Route('GET', '/500')]
    public function page500(): Response
    {
        return new Response('500', 500);
    }
}
