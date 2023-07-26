<?php

namespace App\Repositories\Eloquent;

use App\Models\Transaction as Model;
use App\Repositories\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
  private $model;

  public function __construct(Model $model)
  {
    $this->model = $model;
  }

  public function store(array $data): object
  {
    $user = Auth()->user();
    if ($user->id != $data['payer']) {
      return null;
    }

    $data['payer']  = $data['payer'];
    $data['payee']  = $data['payee'];
    $data['value']  = $data['value'];
    $data['status'] = false;
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
    return $this->model->findOrFail($id);
  }
}