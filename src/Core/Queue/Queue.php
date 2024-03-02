<?php

namespace Sh1ne\MySqlBot\Core\Queue;

interface Queue
{

    public function getName() : string;

    public function push(JobDispatch $jobDispatch) : void;

    public function pop() : JobDispatch;

    public function size() : int;

}