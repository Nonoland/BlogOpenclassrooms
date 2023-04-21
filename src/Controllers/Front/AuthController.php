<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers\Front;

use Nolandartois\BlogOpenclassrooms\Controllers\FrontController;
use Nolandartois\BlogOpenclassrooms\Core\Auth\Authentification;
use Nolandartois\BlogOpenclassrooms\Core\Entity\User;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\Route;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends FrontController
{

    #[Route(['GET', 'POST'], '/login')]
    public function login(): Response
    {
        $request = $this->getRequest();
        $messages = [];

        $currentUser = (int)$request->getSession()->get('user', 0);
        $currentUser = new User($currentUser);
        if (!$currentUser->isGuest()) {
            return self::redirect('/my_account');
        }

        $action = $request->request->get('action');
        if ($action === 'register') {
            $firstname = $request->request->get('firstname', false);
            $lastname = $request->request->get('lastname', false);
            $email = $request->request->get('email', false);
            $password = $request->request->get('password', false);
            $repeatPassword = $request->request->get('rp_password',false);

            if ($password === $repeatPassword && is_string($password)) {
                $result = Authentification::registerNewUser($firstname, $lastname, $email, $password);
                $messages[] = $result ? "Inscription réussie" : "Erreur lors de l'inscription !";
            }
        } elseif ($action === 'login') {
            $email = $request->request->get('email', false);
            $password = $request->request->get('password', false);

            if ($email && $password) {
                $connectUser = Authentification::connectUser($email, $password);

                if ($connectUser) {
                    $request->getSession()->set('user', $connectUser->getId());
                    return self::redirect('/my_account');
                } else {
                    $messages[] = 'Erreur lors de la connexion !';
                }
            } else {
                $messages[] = "Erreur lors de la connexion !";
            }
        }

        $templates = $this->getTwig()->load('front/user/login_register.twig');
        $content = $templates->render([
            'messages' => $messages
        ]);

        return new Response($content);
    }

    #[Route(['GET'], '/logout')]
    public function logout(): Response
    {
        $currentUser = $this->getRequest()->getSession()->get('user', false);
        if (!$currentUser) {
            return self::redirect('/');
        }

        Authentification::logoutUser($currentUser);

        return self::redirect('/');
    }

    #[Route(['GET'], '/my_acount')]
    public function myaccount(): void
    {

    }
}
