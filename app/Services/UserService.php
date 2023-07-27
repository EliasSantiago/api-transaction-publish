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

  public function getById(string $userId): object | null
  {
    return $this->repository->getById($userId);
  }

  public function getUserData(int $userId): object | null
  {
    return $this->repository->getUserData($userId);
  }
}