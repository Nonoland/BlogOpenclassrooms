<?php
namespace Nolandartois\BlogOpenclassrooms\Controllers;

use Nolandartois\BlogOpenclassrooms\Core\Routing\Route;

class IndexController extends Controller
{
    #[Route('/')]
    public function index()
    {
        echo 'index';
    }
}
