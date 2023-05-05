<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers\Admin;

use Nolandartois\BlogOpenclassrooms\Controllers\AdminController;
use Nolandartois\BlogOpenclassrooms\Core\Entity\Contact;
use Nolandartois\BlogOpenclassrooms\Core\Entity\User;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\Route;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\RouteAccess;
use Symfony\Component\HttpFoundation\Response;

class AdminContactController extends AdminController
{

    #[Route('GET', '/admin/contact'), RouteAccess('admin')]
    public function indexContact(): Response
    {
        $contacts = Contact::getAll();

        $template = $this->getTwig()->load('admin/contact/contact.twig');
        $content = $template->render([
            'contacts' => $contacts
        ]);

        return new Response($content);
    }

    #[Route(['GET', 'POST'], '/admin/contact/view/{id_contact:int}'), RouteAccess('admin')]
    public function postEdit(array $params): Response
    {
        $contact = new Contact($params['id_contact']);

        if ($contact->getId() == 0) {
            return self::redirect('/admin/contact');
        }

        $template = $this->getTwig()->load('admin/contact/view.twig');
        $content = $template->render([
            'contact' => $contact
        ]);

        return new Response($content);
    }

    #[Route(['GET'], '/admin/contact/delete/{id_contact:int}'), RouteAccess('admin')]
    public function postDelete(array $params): Response
    {
        $contact = new Contact((int)$params['id_contact']);
        $contact->delete();

        return self::redirect('/admin/contact');
    }
}
