<?php

namespace Sh1ne\MySqlBot\Core\Console;

use Dotenv\Dotenv;
use Sh1ne\MySqlBot\Core\BaseApplication;

class Kernel
{

    protected BaseApplication $application;

    public function __construct(BaseApplication $application)
    {
        $this->application = $application;
    }

    public function boot() : void
    {
        Dotenv::createImmutable($this->application->getBaseDirectory())->safeLoad();

        $this->application->registerServices();

        $this->application->boot();
    }

}