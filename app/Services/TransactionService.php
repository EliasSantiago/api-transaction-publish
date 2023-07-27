<?php

namespace App\Services;

use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\WalletNotFoundException;
use App\Exceptions\ZeroValueException;
use App\Models\Transaction;
use App\Repositories\TransactionRepositoryInterface;
use App\Services\TransactionPublicationWrapper as ServicesTransactionPublicationWrapper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use TransactionPublicationWrapper;

class TransactionService
{
  private $repository;
  private $userService;
  private $transactionQueueService;

  public function __construct(
    TransactionRepositoryInterface $repository,
    UserService $userService,
    TransactionQueueService $transactionQueueService
  ) {
    $this->repository = $repository;
    $this->userService = $userService;
    $this->transactionQueueService = $transactionQueueService;
  }

  public function getAll(): object | null
  {
    try {
      return $this->repository->getAll();
    } catch (\Throwable $th) {
      return null;
    }
  }

  public function store(array $data): object
  {
    $user = Auth()->user();
    $payer = $this->userService->getUserData($user->id);
    $payee = $this->userService->getUserData($data['payee']);
    $walletPayer = $payer->wallets[0] ?? null;
    $walletPayee = $payee->wallets[0] ?? null;

    if ($data['value'] == 0) {
      throw new ZeroValueException();
    }

    if (!$walletPayee || !$walletPayer) {
      throw new WalletNotFoundException();
    }

    if ($walletPayer->balance < $data['value']) {
      throw new InsufficientBalanceException();
    }

    $response = $this->repository->store($data);
    $response->status = false;

    try {
      $isPublished = $this->transactionQueueService->sendTransactionToQueue($response);
      if (!$isPublished) {
        $response->failed_transaction = true;
        $response->save();
      }
    } catch (\Exception $e) {
      Log::error($e->getMessage());
    } finally {
      unset($queueService);
    }

    return $response;
  }

  public function update(string $id, array $data): object | null
  {
    return $this->repository->update($id, $data);
  }
}
