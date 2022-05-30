<?php

namespace App\Repository\Shopify;

use App\Models\User;
use App\Repository\AbstractRepository;

class UserRepository extends AbstractRepository
{
    /**
     * @return User
     */
    protected function model(): string
    {
        return User::class;
    }
}
