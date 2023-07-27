<?php

namespace App\Repositories;

use App\Models\Transaction;

interface TransactionRepositoryInterface
{
  public function getAll(): object | null;
  public function store(array $data): object;
}