<?php

namespace App\Repositories;

use App\Models\Transaction;

interface TransactionRepositoryInterface
{
  public function getAll(): object | null;
  public function store(array $data): object;
  public function addToLocalQueue(Transaction $transaction): void;
  public function update(string $id, array $data): object | null;
}