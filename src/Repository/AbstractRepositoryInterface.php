<?php

namespace App\Repository;

interface AbstractRepositoryInterface
{
    public function save($entity, bool $flush = false): void;
    public function delete($entity, bool $flush = false): void;
}