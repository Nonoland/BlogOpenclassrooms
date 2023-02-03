<?php
namespace Nolandartois\BlogOpenclassrooms\Controllers;

use Nolandartois\BlogOpenclassrooms\Core\Routing\Route;

class IndexController extends Controller
{
    #[Route('GET', '/article/{id:int}/{slug:string}')]
    public function index($params): void
    {
        $templates = $this->getTwig()->load('index.twig');

        dump($params);

        echo $templates->render();
    }

    #[Route('GET', '/article')]
    public function article()
    {

}
}
