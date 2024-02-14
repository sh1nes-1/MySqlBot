<?php

require_once __DIR__ . '/vendor/autoload.php';

use Sh1ne\MySqlBot\Controllers\SlackController;
use Sh1ne\MySqlBot\Core\Http\BasicRequest;
use Sh1ne\MySqlBot\Core\Http\BasicResponseFactory;
use Sh1ne\MySqlBot\Core\Http\Router;
use Sh1ne\MySqlBot\ExceptionHandler;
use Sh1ne\MySqlBot\Middleware\SlackAuthorization;
use Sh1ne\MySqlBot\Middleware\SlackVerificationMiddleware;

error_reporting(0);

Dotenv\Dotenv::createImmutable(__DIR__)->safeLoad();

$responseFactory = new BasicResponseFactory();

$exceptionHandler = new ExceptionHandler($responseFactory);
$router = new Router($exceptionHandler);

$router->middleware('/api/v1/slack/events/', new SlackVerificationMiddleware($responseFactory));
$router->middleware('/api/v1/slack/', new SlackAuthorization($responseFactory));

$router->post('/api/v1/slack/events/app_mention', [SlackController::class, 'mentionEvent']);

$request = new BasicRequest();
$router->handleRequest($request);