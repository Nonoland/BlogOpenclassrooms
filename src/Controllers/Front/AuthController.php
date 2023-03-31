<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers\Front;

use Nolandartois\BlogOpenclassrooms\Controllers\FrontController;
use Nolandartois\BlogOpenclassrooms\Core\Auth\Authentification;
use Nolandartois\BlogOpenclassrooms\Core\Object\User;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\Route;

class AuthController extends FrontController
{


    #[Route(['GET', 'POST'], '/login')]
    public function login(): void
    {
        $request = $this->getRequest();
        $messages = [];

        if (!$request->getUser()->isGuest()) {
            self::redirect('/my_account');
        }

        if ($request->getIsset('action') && $request->getValuePost('action') == 'register') {

            if (!User::userExistByEmail($request->getValuePost('email')) &&
                ($request->getValuePost('password') == $request->getValuePost('rp_password'))) {

                $user = new User();
                $user->setFirstname($request->getValuePost('firstname'));
                $user->setLastname($request->getValuePost('lastname'));
                $user->setEmail($request->getValuePost('email'));
                $user->setPassword($request->getValuePost('password'));
                $user->setRoles(['user']);
                $user->add();

                $messages[] = 'Inscription réussie !';
            }
        } elseif ($request->getIsset('action') && $request->getValuePost('action') == 'login') {
            if ($request->getIsset('email') && $request->getIsset('password')) {

                $cookieKey = Authentification::connectUser(
                    $request->getValuePost('email'),
                    $request->getValuePost('password')
                );

                if ($cookieKey) {
                    $request->getCookie()->setAuthentificationCookieKey($cookieKey);
                    $request->getCookie()->writeCookie();
                    self::redirect('my_account');
                } else {
                    $messages[] = "Authentification échouée, email et/ou mot de passe incorrect.";
                }
            }
        }

        $templates = $this->getTwig()->load('front/user/login_register.twig');
        echo $templates->render([
            'messages' => $messages
        ]);
    }

    #[Route(['GET', 'POST'], '/register')]
    public function register(): void
    {
        $request = $this->getRequest();

        if ($request->getIsset('firstname')
            && $request->getIsset('lastname')
            && $request->getIsset('email')
            && $request->getIsset('password')
            && $request->getIsset('repeat_password')
            && ($request->getValuePost('password') == $request->getValuePost('repeat_password'))) {

            $user = new User();
            $user->setFirstname($request->getValuePost('firstname'));
            $user->setLastname($request->getValuePost('lastname'));
            $user->setEmail($request->getValuePost('email'));
            $user->setPassword($request->getValuePost('password'));
            $user->setRoles(['user']);
            $user->add();
        }


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
