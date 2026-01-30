<?php

namespace App\Repositories;

use App\Models\User;

class UsersRepository
{
    private function buscar_db(int $id): User
    {
        return User::findOrFail($id);
    }
}