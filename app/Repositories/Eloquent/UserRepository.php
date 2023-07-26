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

  public function getById(int $id): object | null
  {
    return $this->model->findOrFail($id);
  }

  public function getUserData(int $id): ?object
  {
    return $this->model->with('wallets')->find($id);
  }

  public function update(string $id, array $data): object | null
  {
    if (!$user = $this->getById($id)) {
      return null;
    }
    $user->update($data);
    return $user;  
  }

  public function delete(string $id): bool
  {
    if (!$user = $this->getById($id)) {
      return false;
    }
    return $user->delete();
  }

}