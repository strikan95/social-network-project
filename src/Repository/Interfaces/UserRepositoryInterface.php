<?php

namespace App\Repository\Interfaces;

use App\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user, bool $flush);

    public function remove(User $user, bool $flush);
}