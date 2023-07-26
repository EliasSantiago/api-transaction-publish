<?php

namespace App\Services;

use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\WalletNotFoundException;
use App\Exceptions\ZeroValueException;
use App\Repositories\TransactionRepositoryInterface;

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

  public function show(int $id): object | null
  {
    return $this->repository->show($id);
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
    $this->transactionQueueService->sendTransactionToQueue($response);

    return $response;
  }

  public function update(string $id, array $data): object | null
  {
    return $this->repository->update($id, $data);
  }
}
