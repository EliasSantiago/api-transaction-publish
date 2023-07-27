<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Services\TransactionQueueService;
use Illuminate\Console\Command;

class RetryFailedTransactions extends Command
{
    protected $signature = 'retry:failed-transactions';
    protected $description = 'Retry sending failed transactions to the RabbitMQ queue';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(TransactionQueueService $queueService)
    {
        $failedTransactions = Transaction::where('failed_transaction', true)
            ->where('retry_count', '<', 3) // limite de tentativas
            ->get();

        foreach ($failedTransactions as $transaction) {
            $isPublished = $queueService->sendTransactionToQueue($transaction);

            if ($isPublished) {
                $transaction->failed_transaction = false;
                $transaction->retry_count = 0;
            }

            $transaction->retry_count++;
            $transaction->save();
        }
    }
}
