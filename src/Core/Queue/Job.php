<?php

namespace Sh1ne\MySqlBot\Core\Queue;

abstract class Job
{

    public function dispatch() : JobDispatch
    {
        return new JobDispatch($this);
    }

    abstract public function handle() : void;

}