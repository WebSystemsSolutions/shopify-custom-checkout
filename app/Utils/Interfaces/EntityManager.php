<?php

namespace App\Utils\Interfaces;

interface EntityManager
{
    public function persist(object $entity): void;

    public function remove(object $entity): void;

    public function flush(): void;
}
