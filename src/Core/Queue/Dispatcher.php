<?php

namespace Sh1ne\MySqlBot\Core\Queue;

interface Dispatcher
{

    public function dispatch(JobDispatch $jobDispatch) : void;

}