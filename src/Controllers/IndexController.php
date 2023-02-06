<?php
namespace Nolandartois\BlogOpenclassrooms\Controllers;

use Nolandartois\BlogOpenclassrooms\Core\Routing\Route;

class IndexController extends Controller
{
    #[Route('GET', '/')]
    public function index(): void
    {
        $templates = $this->getTwig()->load('front/home.twig');

        echo $templates->render();
    }

    #[Route('GET', '/404')]
    public function page404(): void
    {
        echo '404';
    }
}
