<?php

namespace App\Repositories\Eloquent;

use App\Models\Transaction as Model;
use App\Repositories\TransactionRepositoryInterface;
use App\Models\Transaction;

class TransactionRepository implements TransactionRepositoryInterface
{
  private $model;

  public function __construct(Model $model)
  {
    $this->model = $model;
  }

  public function store(array $data): object
  {
    $data['payer']  = $data['payer'];
    $data['payee']  = $data['payee'];
    $data['value']  = $data['value'];
    $data['status'] = false;
    return $this->model->create($data);
  }

  public function getAll(): object | null
  {
    return $this->model->orderBy('created_at', 'DESC')->paginate(100);
  }
}
