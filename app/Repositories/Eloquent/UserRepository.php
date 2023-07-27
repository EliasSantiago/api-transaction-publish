<?php

namespace App\Repositories\Eloquent;

use App\Models\User as Model;
use App\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
  private $model;

  public function __construct(Model $model)
  {
    $this->model = $model;
  }

  public function getById(int $userId): object | null
  {
    return $this->model->findOrFail($userId);
  }

  public function getUserData(int $userId): ?object
  {
    return $this->model->with('wallets')->find($userId);
  }
}
