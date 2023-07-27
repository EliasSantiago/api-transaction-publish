<?php

namespace App\Services;

use App\Services\TransactionPublicationWrapper as ServicesTransactionPublicationWrapper;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use TransactionPublicationWrapper;

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

    public function sendTransactionToQueue(object $data, bool &$isPublishedSuccessfully): void
    {
        //$queueName = 'transactions';
        // $message = new AMQPMessage($data);
        // $this->channel->basic_publish($message, '', $queueName);

        $queueName = 'transactions';
        $message = new AMQPMessage(json_encode($data));
    
        $this->channel->basic_publish($message, '', $queueName);
    
        // Aguarda o resultado da publicação
        try {
            $this->channel->wait_for_pending_acks_returns(5); // Tempo de espera em segundos
            $isPublishedSuccessfully = true;
        } catch (\PhpAmqpLib\Exception\AMQPOutOfBoundsException $e) {
            $isPublishedSuccessfully = false;
        }
    }
}
