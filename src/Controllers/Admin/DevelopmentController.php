<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers\Admin;

use Nolandartois\BlogOpenclassrooms\Controllers\AdminController;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Route;

class DevelopmentController extends AdminController
{

    #[Route('GET', '/admin/dev/showControllers')]
    public function showControllers(): void
    {
        dump($this->getDispatcher()->getRoutes());
    }
}
