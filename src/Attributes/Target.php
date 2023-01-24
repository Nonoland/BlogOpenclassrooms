<?php
namespace Nolandartois\BlogOpenclassrooms\Attributes;

use Attribute;

#[Attribute]
class Target {

    private $target;

    public function __construct(string $target) {
        $this->target = $target;
    }

    public function getTarget() : string
    {
        return $this->target;
    }
}