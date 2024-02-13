<?php

require_once __DIR__ . '/vendor/autoload.php';

use Sh1ne\MySqlBot\Controllers\SlackController;
use Sh1ne\MySqlBot\Core\Http\Request;
use Sh1ne\MySqlBot\Core\Http\Router;
use Sh1ne\MySqlBot\ExceptionHandler;
use Sh1ne\MySqlBot\Middleware\SlackAuthorization;

Dotenv\Dotenv::createImmutable(__DIR__)->safeLoad();

$exceptionHandler = new ExceptionHandler();
$router = new Router($exceptionHandler);

$router->middleware('/api/v1/slack/', new SlackAuthorization());
$router->get('/api/v1/slack/events/app_mention', [SlackController::class, 'mentionEvent']);

$request = new Request();
$router->handleRequest($request);