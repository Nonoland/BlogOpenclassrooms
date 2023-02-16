<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers;

use Nolandartois\BlogOpenclassrooms\Core\Routing\Route;

class AdminController extends Controller
{
    #[Route('GET', '/admin')]
    public function indexAdmin(): void
    {
        echo 'test admin';
    }
}
