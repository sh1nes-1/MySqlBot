<?php

require_once __DIR__ . '/vendor/autoload.php';

use Sh1ne\MySqlBot\Application;
use Sh1ne\MySqlBot\Controllers\SlackController;
use Sh1ne\MySqlBot\Controllers\StatusController;
use Sh1ne\MySqlBot\Core\Http\Kernel;
use Sh1ne\MySqlBot\Core\Http\Router;
use Sh1ne\MySqlBot\Middleware\LogRequestsMiddleware;
use Sh1ne\MySqlBot\Middleware\SlackAuthorization;
use Sh1ne\MySqlBot\Middleware\SlackVerificationMiddleware;

$application = new Application();

$kernel = new Kernel($application);
$kernel->boot();

$router = app(Router::class);

$router->middleware('/', new LogRequestsMiddleware());
$router->middleware('/api/v1/slack/events', new SlackVerificationMiddleware());
$router->middleware('/api/v1/slack/', new SlackAuthorization());

$router->get('/api/v1/status', [StatusController::class, 'index']);
$router->post('/api/v1/slack/events', [SlackController::class, 'handleEvent']);

$kernel->handleRequest();