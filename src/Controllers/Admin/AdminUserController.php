<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers\Admin;

use Nolandartois\BlogOpenclassrooms\Controllers\AdminController;
use Nolandartois\BlogOpenclassrooms\Core\Entity\User;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\Route;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\RouteAccess;
use Symfony\Component\HttpFoundation\Response;

class AdminUserController extends AdminController
{


    #[Route(['GET'], '/admin/users'), RouteAccess('admin')]
    public function indexUsers(): Response
    {
        $users = User::getAllUsers();

        foreach ($users as &$user) {
            $user['roles'] = json_decode($user['roles'], JSON_OBJECT_AS_ARRAY);
            $user['roles'] = implode(',', $user['roles']);
        }

        $template = $this->getTwig()->load('admin/users/users.twig');
        $content = $template->render([
            'users' => $users
        ]);

        return new Response($content);
    }

    #[Route(['GET', 'POST'], '/admin/users/new'), RouteAccess('admin')]
    public function newUser(): Response
    {
        $request = $this->getRequest();

        $userSubmit = $request->request->has('user_submit');
        $userLastname = $request->request->get('user_lastname', false);
        $userFirstname = $request->request->get('user_firstname', false);
        $userEmail = $request->request->get('user_email', false);
        $userPassword = $request->request->get('user_password', false);

        if ($userSubmit && $userLastname && $userFirstname && $userEmail && $userPassword) {
            $user = new User();
            $user->setFirstname($userFirstname);
            $user->setLastname($userLastname);
            $user->setEmail($userEmail);
            $user->setRoles(['user']);
            $user->setPassword($userPassword);
            $user->add();

            return self::redirect('/admin/users');
        }

        $template = $this->getTwig()->load('admin/users/new.twig');
        $content = $template->render();

        return new Response($content);
    }

    #[Route(['GET', 'POST'], '/admin/users/edit/{id_user:int}'), RouteAccess('admin')]
    public function editUser(array $params): Response
    {
        $request = $this->getRequest();
        $user = new User($params['id_user']);

        if ($user->isGuest()) {
            return self::redirect('/admin/users');
        }

        $userSubmit = $request->request->has('user_submit');
        $userLastname = $request->request->get('user_lastname', false);
        $userFirstname = $request->request->get('user_firstname', false);
        $userEmail = $request->request->get('user_email', false);
        $userPassword = $request->request->get('user_password', false);

        if ($userSubmit && $userLastname && $userFirstname && $userEmail) {
            $user->setLastname($userLastname);
            $user->setFirstname($userFirstname);
            $user->setEmail($userEmail);

            if (!empty($userPassword)) {
                $user->setPassword($userPassword);
            }

            $user->update();

            return self::redirect('/admin/users');
        }

        $template = $this->getTwig()->load('admin/users/edit.twig');
        $content = $template->render([
            'user' => $user
        ]);

        return new Response($content);
    }

    #[Route(['GET'], '/admin/users/delete/{id_user:int}'), RouteAccess('admin')]
    public function deleteUser(array $params): Response
    {
        $user = new User($params['id_user']);
        $user->delete();

        return self::redirect('/admin/users');
    }

}
