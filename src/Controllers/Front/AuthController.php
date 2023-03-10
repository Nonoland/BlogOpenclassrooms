<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers\Front;

use Nolandartois\BlogOpenclassrooms\Controllers\FrontController;
use Nolandartois\BlogOpenclassrooms\Core\Object\User;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Route;

class AuthController extends FrontController
{


    #[Route(['GET', 'POST'], '/login')]
    public function login(): void
    {
        echo 'test';
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
