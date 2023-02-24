<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers\Front;

use Nolandartois\BlogOpenclassrooms\Controllers\FrontController;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Route;

class IndexController extends FrontController
{
    #[Route('GET', '/')]
    public function index(): void
    {
        $templates = $this->getTwig()->load('front/home/home.twig');

        echo $templates->render();
    }

    #[Route('GET', '/404')]
    public function page404(): void
    {
        echo '404';
    }
}
