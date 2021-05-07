<?php

namespace App\DTO;

use JsonSerializable;

class PermissionsDTO implements JsonSerializable
{
    private array $collection = [];

    public function add(int $id, string $permission)
    {
        array_push($this->collection, [
            'id' => $id,
            'permission' => $permission
        ]);
    }

    public function jsonSerialize()
    {
        return $this->collection;
    }
}