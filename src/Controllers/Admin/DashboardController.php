<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers\Admin;

use Nolandartois\BlogOpenclassrooms\Controllers\AdminController;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\Route;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\RouteAccess;

class DashboardController extends AdminController
{
    #[Route('GET', '/admin'), RouteAccess('admin')]
    public function indexAdmin(): void
    {
        $template = $this->getTwig()->load('admin/dashboard/dashboard.twig');

        echo $template->render();
    }
}
