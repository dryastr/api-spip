<?php

namespace App\Helpers\Rabbitmq;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Exception\AMQPChannelClosedException;
use PhpAmqpLib\Exception\AMQPConnectionClosedException;
use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\RabbitMQQueue as BaseRabbitMQQueue;

class RabbitMQQueue extends BaseRabbitMQQueue
{
    /**
     * Publish basic messages
     */
    protected function publishBasic($msg, $exchange = '', $destination = '', $mandatory = false, $immediate = false, $ticket = null): void
    {
        try {
            parent::publishBasic($msg, $exchange, $destination, $mandatory, $immediate, $ticket);
        } catch (AMQPConnectionClosedException|AMQPChannelClosedException) {
            $this->reconnect();
            parent::publishBasic($msg, $exchange, $destination, $mandatory, $immediate, $ticket);
        }
    }

    /**
     * Publish batch messages
     */
    protected function publishBatch($jobs, $data = '', $queue = null): void
    {
        try {
            parent::publishBatch($jobs, $data, $queue);
        } catch (AMQPConnectionClosedException|AMQPChannelClosedException) {
            $this->reconnect();
            parent::publishBatch($jobs, $data, $queue);
        }
    }

    /**
     * Create channel
     */
    protected function createChannel(): AMQPChannel
    {
        try {
            return parent::createChannel();
        } catch (AMQPConnectionClosedException) {
            $this->reconnect();
            return parent::createChannel();
        }
    }
}
