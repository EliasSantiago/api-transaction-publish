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

  public function show(string $walletId): object | null
  {
    $user = Auth()->user();
    return $this->model->where('user_id', $user->id)->findOrFail($walletId);
  }
}