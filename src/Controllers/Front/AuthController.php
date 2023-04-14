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

        if ($request->hasSession() && $request->getSession()->has('user')) {
            /** @var User $currentUser */
            $currentUser = $request->getSession()->get('user');
            if ($currentUser instanceof User && !$currentUser->isGuest()) {
                self::redirect('/my_account');
            }
        }

        if ($request->request->has('action')) {
            if ($request->request->get('action') == 'register') {
                $firstname = $request->request->get('firstname', false);
                $lastname = $request->request->get('lastname', false);
                $email = $request->request->get('email', false);
                $password = $request->request->get('password', false);
                $repeatPassword = $request->request->get('rp_password', false);

                if ($password === $repeatPassword && is_string($password)) {
                    $result = Authentification::registerNewUser($firstname, $lastname, $email, $password);
                    if ($result) {
                        $messages[] = "Inscription réussie";
                    } else {
                        $messages[] = "Erreur lors de l'inscription !";
                    }
                }
            } elseif ($request->request->get('action') == 'login') {
                $email = $request->request->get('email', false);
                $password = $request->request->get('password', false);

                if ($email && $password) {
                    $cookieKey = Authentification::connectUser(
                        $email,
                        $password
                    );

                    if ($cookieKey) {
                        $request->getSession()->set('user', $cookieKey);
                        self::redirect('/my_account');
                    } else {
                        $messages[] = 'Erreur lors de la connexion !';
                    }
                } else {
                    $messages[] = "Erreur lors de la connexion !";
                }
            }
        }

        $templates = $this->getTwig()->load('front/user/login_register.twig');
        $content = $templates->render([
            'messages' => $messages
        ]);

        return new Response($content);
    }

    #[Route(['GET'], '/logout')]
    public function logout(): void
    {

    }

    #[Route(['GET'], '/my_acount')]
    public function myaccount(): void
    {

    }
}
