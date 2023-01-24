<?php
namespace Nolandartois\BlogOpenclassrooms\Controllers;

use Nolandartois\BlogOpenclassrooms\Attributes\Target;

class IndexController extends Controller
{
    #[Target('/')]
    public function index()
    {
        echo 'lol';
    }
}
