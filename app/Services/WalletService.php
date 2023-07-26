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

  public function show(int $id): object | null
  {
    return $this->repository->show($id);
  }
  
  public function store(array $data): object
  {
    return $this->repository->store($data);
  }

  public function update(string $id, array $data): object | null
  {
    return $this->repository->update($id, $data);
  }

  public function delete(string $id): bool
  {
    return $this->repository->delete($id);
  }
}