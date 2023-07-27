<?php

namespace App\Services;

use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\WalletNotFoundException;
use App\Exceptions\ZeroValueException;
use App\Models\Transaction;
use App\Repositories\TransactionRepositoryInterface;
use App\Services\TransactionPublicationWrapper as ServicesTransactionPublicationWrapper;
use Illuminate\Database\Eloquent\Collection;
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
    return $this->repository->getAll();
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

    // Variável booleana para armazenar o resultado da publicação
    $isPublishedSuccessfully = false;

    // Chama o método sendTransactionToQueue() e passa os dados da transação e a variável booleana
    $this->transactionQueueService->sendTransactionToQueue($response, $isPublishedSuccessfully);

    // Após a publicação, verificamos o resultado e atualizamos o registro no banco de dados conforme necessário
    if (!$isPublishedSuccessfully) {
      // Se a publicação na fila falhou, atualizamos o campo "failed_transaction" no registro correspondente
      Transaction::where('id', $response->id)
        ->update(['failed_transaction' => true]);
    }

    return $response;
  }

  public function update(string $id, array $data): object | null
  {
    return $this->repository->update($id, $data);
  }
}
