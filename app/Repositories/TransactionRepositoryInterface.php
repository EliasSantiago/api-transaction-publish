<?php

namespace App\Repositories;

interface TransactionRepositoryInterface
{
  public function getAll(): object | null;
  public function store(array $data): object;
  public function update(string $id, array $data): object | null;
}