<?php

namespace App\Services;

use App\Repositories\WalletRepositoryInterface;

class WalletService
{
  private $repository;

  public function __construct(WalletRepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  public function show(int $walletId): object | null
  {
    return $this->repository->show($walletId);
  }
  
  public function store(array $data): object
  {
    return $this->repository->store($data);
  }
}