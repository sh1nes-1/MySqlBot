<?php

namespace Sh1ne\MySqlBot\Core\Queue\Amqp;

use Exception;
use Sh1ne\MySqlBot\Core\Queue\Dispatcher;
use Sh1ne\MySqlBot\Core\Queue\JobDispatch;

class AmqpDispatcher implements Dispatcher
{

    /**
     * @throws Exception
     */
    public function dispatch(JobDispatch $jobDispatch) : void
    {
        $queue = new AmqpQueue($jobDispatch->getQueue());

        $queue->push($jobDispatch);
    }

}