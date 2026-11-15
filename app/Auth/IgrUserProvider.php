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
        $attributes['role'] = $this->connection->table('roles')
            ->where('id', $attributes['role_id'])
            ->value('code');

        return new GenericUser($attributes);
    }
}
