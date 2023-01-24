<?php
namespace Nolandartois\BlogOpenclassrooms\Controllers;

use Nolandartois\BlogOpenclassrooms\Attributes\Route;

class IndexController extends Controller
{
    #[Route('/')]
    public function index()
    {
        echo 'lol';
    }
}
