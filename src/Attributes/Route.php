<?php
namespace Nolandartois\BlogOpenclassrooms\Attributes;

use Attribute;

#[Attribute]
class Route {

    private string $path;
    private $target;

    public function __construct(string $path) {
        $this->path = $path;
    }

    public function getRoute() : string
    {
        return $this->target;
    }

    public function getPath() : array
    {
        return explode('/', $this->path);
    }

    public function getAttributes()
    {

    }
}