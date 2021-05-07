<?php

namespace App\DTO;

class UpdateUserDTO 
{
    public string $active;
    public bool $blocked;
    public string $name;
    public PermissionsDTO $permissions;
}