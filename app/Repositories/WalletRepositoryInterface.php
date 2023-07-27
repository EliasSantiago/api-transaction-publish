<?php

namespace App\Repositories;

interface WalletRepositoryInterface
{
  public function show(string $walletId): object | null;
  public function store(array $data): object;
}