<?php

namespace App\Interfaces;

interface User
{
    public function id(): int;

    public function getRole(): Role;

    public function can($scope): bool;
}