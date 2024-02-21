<?php

require_once __DIR__ . '/vendor/autoload.php';

use Sh1ne\MySqlBot\Controllers\SlackController;
use Sh1ne\MySqlBot\Controllers\StatusController;
use Sh1ne\MySqlBot\Core\Http\BasicOutput;
use Sh1ne\MySqlBot\Core\Http\BasicRequest;
use Sh1ne\MySqlBot\Core\Http\BasicResponseFactory;
use Sh1ne\MySqlBot\Core\Http\Router;
use Sh1ne\MySqlBot\Core\Log;
use Sh1ne\MySqlBot\ErrorHandler;
use Sh1ne\MySqlBot\ExceptionHandler;
use Sh1ne\MySqlBot\Middleware\LogRequestsMiddleware;
use Sh1ne\MySqlBot\Middleware\SlackAuthorization;
use Sh1ne\MySqlBot\Middleware\SlackVerificationMiddleware;

$output = new BasicOutput();
$responseFactory = new BasicResponseFactory();

$errorHandler = new ErrorHandler($output, $responseFactory);
$errorHandler->disableErrorReporting();
$errorHandler->registerShutdownCallback();

Dotenv\Dotenv::createImmutable(__DIR__)->safeLoad();

Log::init(__DIR__ . '/logs/app.log');

$exceptionHandler = new ExceptionHandler($responseFactory);
$router = new Router($exceptionHandler, $output);

$router->middleware('/', new LogRequestsMiddleware($responseFactory));
$router->middleware('/api/v1/slack/events', new SlackVerificationMiddleware($responseFactory));
$router->middleware('/api/v1/slack/', new SlackAuthorization($responseFactory));

$router->get('/api/v1/status', [StatusController::class, 'index']);
$router->post('/api/v1/slack/events', [SlackController::class, 'handleEvent']);

$request = new BasicRequest();
$router->handleRequest($request);