<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes;

use Attribute;

#[Attribute]
class RouteAccess
{
    private array $roles;

    public function __construct(string|array $roles)
    {
        if (is_string($roles)) {
            $this->roles = [$roles];
        } else {
            $this->roles = $roles;
        }
    }

    public function getRoles(): array
    {
        return $this->roles;
    }
}