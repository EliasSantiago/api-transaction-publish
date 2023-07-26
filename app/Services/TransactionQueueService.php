<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class TransactionQueueService
{
    private $connection;
    private $channel;

    public function __construct(string $host, int $port, string $user, string $password)
    {
        $this->connection = new AMQPStreamConnection($host, $port, $user, $password);
        $this->channel = $this->connection->channel();
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }

    public function sendTransactionToQueue(array $data): void
    {
        $jsonData = json_encode($data);
        $queueName = 'transactions';
        $message = new AMQPMessage($jsonData);
        $this->channel->basic_publish($message, '', $queueName);
    }
}