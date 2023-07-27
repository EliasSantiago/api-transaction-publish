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
        $this->connectToRabbitMQ($host, $port, $user, $password);
    }

    public function __destruct()
    {
        if ($this->connection) {
            $this->channel->close();
            $this->connection->close();
        }
    }


    public function sendTransactionToQueue(object $data): bool
    {
        $authorizationService = new AuthorizationService();
        $isAuthorized = $authorizationService->checkAuthorization();

        if (!$this->isRabbitMQAvailable() || !$isAuthorized) {
            return false;
        }
    
        $queueName = 'transactions';
        $message = new AMQPMessage(json_encode($data));
        $this->channel->basic_publish($message, '', $queueName);

        return true;
    }

    private function connectToRabbitMQ(string $host, int $port, string $user, string $password): void
    {
        try {
            $this->connection = new AMQPStreamConnection($host, $port, $user, $password);
            $this->channel = $this->connection->channel();
        } catch (\Exception $e) {
            $this->connection = null;
            $this->channel = null;
        }
    }

    private function isRabbitMQAvailable(): bool
    {
        return $this->connection !== null;
    }
}
