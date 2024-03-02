<?php

namespace Sh1ne\MySqlBot\Core\Queue\Amqp;

use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Sh1ne\MySqlBot\Core\Config\AppConfig;
use Sh1ne\MySqlBot\Core\Queue\JobDispatch;
use Sh1ne\MySqlBot\Core\Queue\Queue;

class AmqpQueue implements Queue
{

    private string $name;

    private AMQPStreamConnection $connection;

    private AMQPChannel $channel;

    /**
     * @throws Exception
     */
    public function __construct(string $name)
    {
        $this->name = $name;

        $this->connection = new AMQPStreamConnection(
            AppConfig::getAmqpHost(),
            AppConfig::getAmqpPort(),
            AppConfig::getAmqpUser(),
            AppConfig::getAmqpPassword()
        );

        $this->channel = $this->connection->channel();

        $this->channel->queue_declare($this->name, false, false, false, false);
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function push(JobDispatch $jobDispatch) : void
    {
        $serializedJob = serialize($jobDispatch);

        $message = new AMQPMessage($serializedJob);

        $this->channel->basic_publish($message, '', $this->name);
    }

    public function pop() : JobDispatch
    {
        $message = $this->channel->basic_get($this->name);

        $serializedJob = $message->getBody();

        return unserialize($serializedJob);
    }

    public function size() : int
    {
        [, $queueSize,] = $this->channel->queue_declare($this->name, false, false, false, false);

        return $queueSize;
    }

    /**
     * @throws Exception
     */
    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }

}