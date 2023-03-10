<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers\Admin;

use Nolandartois\BlogOpenclassrooms\Controllers\AdminController;
use Nolandartois\BlogOpenclassrooms\Core\Object\User;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Route;

class AdminUserController extends AdminController
{


    #[Route(['GET'], '/admin/users')]
    public function indexUsers(): void
    {
        $users = User::getAllUsers();

        foreach ($users as &$user) {
            $user['roles'] = json_decode($user['roles'], JSON_OBJECT_AS_ARRAY);
            $user['roles'] = implode(',', $user['roles']);
        }

        $template = $this->getTwig()->load('admin/users/users.twig');
        echo $template->render([
            'users' => $users
        ]);
    }

    #[Route(['GET', 'POST'], '/admin/users/new')]
    public function newUser(): void
    {
        $request = $this->getRequest();

        if ($request->getIsset('user_lastname')
            && $request->getIsset('user_firstname')
            && $request->getIsset('user_email')
            && $request->getIsset('user_password')) {

            $user = new User();
            $user->setFirstname($request->getValuePost('user_firstname'));
            $user->setLastname($request->getValuePost('user_lastname'));
            $user->setEmail($request->getValuePost('user_email'));
            $user->setRoles(['user']);
            $user->setPassword($request->getValuePost('user_password'));
            $user->add();

            $this->redirect('/admin/users');
        }

        $template = $this->getTwig()->load('admin/users/new.twig');
        echo $template->render();
    }

    #[Route(['GET', 'POST'], '/admin/users/edit/{id_user:int}')]
    public function editUser(array $params): void
    {
        if (!$user = new User($params['id_user'])) {
            $this->redirect('/admin/users');
        }

        $request = $this->getRequest();

        if ($request->getIsset('user_lastname')
            && $request->getIsset('user_firstname')
            && $request->getIsset('user_email')
            && $request->getIsset('user_password')) {

            $user->setLastname($request->getValuePost('user_lastname'));
            $user->setFirstname($request->getValuePost('user_firstname'));
            $user->setEmail($request->getValuePost('user_email'));

            if (!empty($request->getValuePost('user_password'))) {
                $user->setPassword($request->getValuePost('user_password'));
            }

            $user->update();

            $this->redirect('/admin/users');
        }

        $template = $this->getTwig()->load('admin/users/edit.twig');
        echo $template->render([
            'user' => $user
        ]);
    }

    #[Route(['GET'], '/admin/users/delete/{id_user:int}')]
    public function deleteUser(array $params): void
    {
        $user = new User($params['id_user']);
        $user->delete();

        $this->redirect('/admin/users');
    }

}