<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;

class UserService
{
  private $repository;

  public function __construct(UserRepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  public function getById(string $id): object | null
  {
    return $this->repository->getById($id);
  }

  public function getUserData(int $id): object | null
  {
    return $this->repository->getUserData($id);
  }
}