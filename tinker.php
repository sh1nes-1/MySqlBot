<?php

use Sh1ne\MySqlBot\Application;
use Sh1ne\MySqlBot\Core\Console\Kernel;

require_once __DIR__ . '/vendor/autoload.php';

$application = new Application(__DIR__);

$kernel = new Kernel($application);
$kernel->boot();

