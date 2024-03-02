<?php

use Sh1ne\MySqlBot\Application;
use Sh1ne\MySqlBot\Core\Config\AppConfig;
use Sh1ne\MySqlBot\Core\Console\Kernel;
use Sh1ne\MySqlBot\Core\Queue\Amqp\AmqpQueue;
use Sh1ne\MySqlBot\Core\Queue\Worker;

require_once __DIR__ . '/vendor/autoload.php';

$application = new Application(__DIR__);

$kernel = new Kernel($application);
$kernel->boot();

$queue = new AmqpQueue(AppConfig::getHandleEventQueueName());

$worker = new Worker($queue);
$worker->work();