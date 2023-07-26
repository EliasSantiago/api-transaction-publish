<?php

namespace App\Repositories;

interface TransactionRepositoryInterface
{
  public function show(string $id): object | null;
  public function store(array $data): object;
  public function update(string $id, array $data): object | null;
}