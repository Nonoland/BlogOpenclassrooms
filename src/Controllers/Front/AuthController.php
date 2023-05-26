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
            $repeatPassword = $request->request->get('rp_password', false);

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

    #[Route(['GET', 'POST'], '/my_account')]
    public function myaccount(): Response
    {
        $request = $this->getRequest();
        /** @var User $currentUser */
        $currentUser = new User($request->getSession()->get('user', 0));

        if ($currentUser->isGuest()) {
            return self::redirect('/login');
        }

        if ($request->request->has('action') && $request->request->get('action') == 'informations') {
            if ($request->request->has('firstname') &&
                $request->request->has('lastname') &&
                $request->request->has('email')) {

                $currentUser->setFirstname($request->request->get('firstname', $currentUser->getFirstname()));
                $currentUser->setLastname($request->request->get('lastname', $currentUser->getLastname()));
                $currentUser->setEmail($request->request->get('email', $currentUser->getEmail()));

                $currentUser->update();
            }
        } elseif ($request->request->has('action') && $request->request->get('action') == 'password') {
            if (($request->request->get('new_password', '') && $request->request->get('repeat_new_password', '')) && strlen($request->request->get('new_password', '')) > 8) {
                $currentUser->setPassword($request->request->get('new_password'));

                $currentUser->update();
            }
        }

        $templates = $this->getTwig()->load('front/user/my_account.twig');
        $content = $templates->render([
            'currentUser' => $currentUser
        ]);

        return new Response($content);
    }

    #[Route(['GET', 'POST'], '/forgotten_password')]
    public function forgottenPassword(): Response
    {
        $request = $this->getRequest();
        /** @var User $currentUser */
        $currentUser = new User($request->getSession()->get('user', 0));

        if (!$currentUser->isGuest()) {
            return self::redirect('/my_account');
        }

        $submit = $request->request->has("submit");
        $email = $request->request->get('email', false);

        if ($submit && $email) {
            Authentification::forgottenPassword($email);
        }

        $templates = $this->getTwig()->load('front/user/forgotten_password.twig');
        $content = $templates->render([]);

        return new Response($content);
    }

    #[Route(['GET', 'POST'], '/change_password/{key:string}')]
    public function changePassword(array $params): Response
    {
        $request = $this->getRequest();

        if (!Authentification::isForgottenPasswordKeyValid($params['key'])) {
            return self::redirect('/');
        }

        $submit = $request->request->has("submit");
        $password = $request->request->get('password', false);
        $rpPassword = $request->request->get('rp_password', false);

        if ($submit && $password && $rpPassword && $password === $rpPassword) {
            Authentification::changePasswordWithForgottenPasswordKey($params['key'], $password);

            return self::redirect('/login');
        }

        $templates = $this->getTwig()->load('front/user/change_password.twig');
        $content = $templates->render([]);
        return new Response($content);
    }
}
