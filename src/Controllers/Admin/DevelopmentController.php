<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers\Admin;

use Nolandartois\BlogOpenclassrooms\Controllers\AdminController;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\Route;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\RouteAccess;

class DevelopmentController extends AdminController
{

    #[Route('GET', '/admin/dev/showControllers'), RouteAccess('admin')]
    public function showControllers(): void
    {
        dump($this->getDispatcher()->getRoutes());
    }
}
