<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers\Admin;

use Nolandartois\BlogOpenclassrooms\Controllers\AdminController;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Route;

class DashboardController extends AdminController
{
    #[Route('GET', '/admin')]
    public function indexAdmin(): void
    {
        $template = $this->getTwig()->load('admin/dashboard/dashboard.twig');

        echo $template->render();
    }
}
