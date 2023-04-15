<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers\Admin;

use Nolandartois\BlogOpenclassrooms\Controllers\AdminController;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\Route;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\RouteAccess;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends AdminController
{
    #[Route('GET', '/admin'), RouteAccess('admin')]
    public function indexAdmin(): Response
    {
        $template = $this->getTwig()->load('admin/dashboard/dashboard.twig');
        $content = $template->render();

        return new Response($content);
    }
}
