<?php

namespace App\Filters\V1;

class UserFilter extends QueryFilter
{
    protected array $sort = [
        'id' => 'id',
        'name' => 'name',
        'email' => 'email',
        'created_at' => 'created_at',
    ];

    public function name(string $name): void
    {
        $this->builder->where('name', 'like', "%{$name}%");
    }

    public function email(string $email): void
    {
        $this->builder->where('email', 'like', "%{$email}%");
    }
}
