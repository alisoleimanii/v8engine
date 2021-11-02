<?php

namespace App\Interfaces;

interface Role
{
    public function getScopes(): array;

    public function addScope(string ...$scope): Role;

    public static function getByTitle($title): Role;
}