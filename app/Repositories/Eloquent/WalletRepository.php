<?php

namespace App\Repositories\Eloquent;

use App\Models\Wallet as Model;
use App\Repositories\WalletRepositoryInterface;

class WalletRepository implements WalletRepositoryInterface
{
  private $model;

  public function __construct(Model $model)
  {
    $this->model = $model;
  }

  public function store(array $data): object
  {
    $user = Auth()->user();
    if (!$user) {
      return null;
    }
    $data['user_id'] = $user->id;
    return $this->model->create($data);
  }

  public function update(string $id, array $data): object | null
  {
    if (!$user = $this->show($id)) {
      return null;
    }
    $user->update($data);
    return $user;  
  }

  public function delete(string $id): bool
  {
    if (!$user = $this->show($id)) {
      return false;
    }
    return $user->delete();
  }

  public function show(string $id): object | null
  {
    $user = Auth()->user();
    return $this->model->where('user_id', $user->id)->findOrFail($id);
  }
}