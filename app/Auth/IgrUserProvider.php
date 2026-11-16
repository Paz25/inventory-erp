<?php

namespace App\Auth;

use Illuminate\Auth\DatabaseUserProvider;
use Illuminate\Auth\GenericUser;

class IgrUserProvider extends DatabaseUserProvider
{
    protected function getGenericUser($user)
    {
        if (is_null($user)) {
            return null;
        }

        $attributes = (array) $user;

        if (! is_null($attributes['deleted_at'])) {
            return null;
        }

        $role = $this->connection->table('roles')
            ->where('id', $attributes['role_id'])
            ->first(['code', 'name']);

        $attributes['role'] = $role?->code;
        $attributes['role_name'] = $role?->name;

        return new GenericUser($attributes);
    }
}
