<?php
namespace Nolandartois\BlogOpenclassrooms\Controllers;

use Nolandartois\BlogOpenclassrooms\Core\Routing\Request;

abstract class Controller{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function getRequest()
    {
        return $this->request;
    }
}
