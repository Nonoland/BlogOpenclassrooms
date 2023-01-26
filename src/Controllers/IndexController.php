<?php
namespace Nolandartois\BlogOpenclassrooms\Controllers;

use Nolandartois\BlogOpenclassrooms\Core\Routing\Route;

class IndexController extends Controller
{
    #[Route('GET', '/')]
    public function index(): void
    {
    }
}
