<?php

namespace Core\Http;

use Exception;
use PHPUnit\Framework\MockObject\Exception as MockException;
use Sh1ne\MySqlBot\Core\Http\JsonResponse;
use Sh1ne\MySqlBot\Core\Http\Middleware;
use Sh1ne\MySqlBot\Core\Http\Output;
use Sh1ne\MySqlBot\Core\Http\Request;
use Sh1ne\MySqlBot\Core\Http\RequestHandler;
use Sh1ne\MySqlBot\Core\Http\Response;
use Sh1ne\MySqlBot\Core\Http\Router;
use PHPUnit\Framework\TestCase;
use Sh1ne\MySqlBot\ExceptionHandler;

class RouterTest extends TestCase
{

    /**
     * @throws MockException
     */
    public function testWithNoRoutesDefined()
    {
        $exceptionHandler = $this->createMock(ExceptionHandler::class);

        $output = $this->createMock(Output::class);

        $output->expects($this->once())
            ->method('sendResponse')
            ->with($this->callback(fn(Response $response) => $response->getStatusCode() === 404));

        $router = new Router($exceptionHandler, $output);

        $request = $this->createMock(Request::class);

        $request->expects($this->once())
            ->method('uri')
            ->willReturn('/products?id=1&page=1');

        $request->expects($this->once())
            ->method('method')
            ->willReturn('GET');

        $router->handleRequest($request);
    }

    /**
     * @throws MockException
     */
    public function testNotFoundWithWrongMethod()
    {
        $exceptionHandler = $this->createMock(ExceptionHandler::class);

        $output = $this->createMock(Output::class);

        $output->expects($this->once())
            ->method('sendResponse')
            ->with($this->callback(fn(Response $response) => $response->getStatusCode() === 404));

        $router = new Router($exceptionHandler, $output);

        $router->post('/products', []);

        $request = $this->createMock(Request::class);

        $request->expects($this->once())
            ->method('uri')
            ->willReturn('/products?id=1&page=1');

        $request->expects($this->once())
            ->method('method')
            ->willReturn('GET');

        $router->handleRequest($request);
    }

    /**
     * @throws MockException
     */
    public function testNotFoundWithWrongUri()
    {
        $exceptionHandler = $this->createMock(ExceptionHandler::class);

        $output = $this->createMock(Output::class);

        $output->expects($this->once())
            ->method('sendResponse')
            ->with($this->callback(fn(Response $response) => $response->getStatusCode() === 404));

        $router = new Router($exceptionHandler, $output);

        $router->get('/product', []);

        $request = $this->createMock(Request::class);

        $request->expects($this->once())
            ->method('uri')
            ->willReturn('/products?id=1&page=1');

        $request->expects($this->once())
            ->method('method')
            ->willReturn('GET');

        $router->handleRequest($request);
    }

    /**
     * @throws MockException
     */
    public function testSuccessfulGet()
    {
        $exceptionHandler = $this->createMock(ExceptionHandler::class);

        $output = $this->createMock(Output::class);

        $output->expects($this->once())
            ->method('sendResponse')
            ->with($this->callback(function(Response $response) {
                return $response->getBodyAsText() === '{"message":"My test response"}';
            }));

        $router = new Router($exceptionHandler, $output);

        $router->get('/products', [TestController::class, 'index']);

        $request = $this->createMock(Request::class);

        $request->expects($this->once())
            ->method('uri')
            ->willReturn('/products?id=1&page=1');

        $request->expects($this->once())
            ->method('method')
            ->willReturn('GET');

        $router->handleRequest($request);
    }

    /**
     * @throws MockException
     */
    public function testSuccessfulGetWithMatchingMiddleware()
    {
        $request = $this->createMock(Request::class);

        $exceptionHandler = $this->createMock(ExceptionHandler::class);

        $output = $this->createMock(Output::class);

        $output->expects($this->once())
            ->method('sendResponse')
            ->with($this->callback(function(Response $response) {

                return $response->getBodyAsText() === '{"message":"My test response"}';
            }));

        $middleware = $this->createMock(Middleware::class);

        $middleware->expects($this->once())
            ->method('setNext')
            ->with($this->callback(function(RequestHandler $requestHandler) use (&$nextRequestHandler) {
                $nextRequestHandler = $requestHandler;

                return true;
            }));

        $middleware->expects($this->once())
            ->method('handle')
            ->with($request)
            ->willReturnCallback(function(Request $request) use (&$nextRequestHandler) {
                return $nextRequestHandler->handle($request);
            });

        $router = new Router($exceptionHandler, $output);

        $router->middleware('/', $middleware);
        $router->get('/products', [TestController::class, 'index']);

        $request->expects($this->exactly(2))
            ->method('uri')
            ->willReturn('/products?id=1&page=1');

        $request->expects($this->once())
            ->method('method')
            ->willReturn('GET');

        $router->handleRequest($request);
    }

    /**
     * @throws MockException
     */
    public function testSuccessfulGetWithNotMatchingMiddleware()
    {
        $exceptionHandler = $this->createMock(ExceptionHandler::class);

        $output = $this->createMock(Output::class);

        $output->expects($this->once())
            ->method('sendResponse')
            ->with($this->callback(function(Response $response) {
                return $response->getBodyAsText() === '{"message":"My test response"}';
            }));

        $middleware = $this->createMock(Middleware::class);

        $middleware->expects($this->never())
            ->method('setNext');

        $middleware->expects($this->never())
            ->method('handle');

        $router = new Router($exceptionHandler, $output);
        $router->middleware('/test', $middleware);
        $router->get('/products', [TestController::class, 'index']);

        $request = $this->createMock(Request::class);

        $request->expects($this->exactly(2))
            ->method('uri')
            ->willReturn('/products?id=1&page=1');

        $request->expects($this->once())
            ->method('method')
            ->willReturn('GET');

        $router->handleRequest($request);
    }

    /**
     * @throws MockException
     */
    public function testSuccessfulPost()
    {
        $exceptionHandler = $this->createMock(ExceptionHandler::class);

        $output = $this->createMock(Output::class);

        $output->expects($this->once())
            ->method('sendResponse')
            ->with($this->callback(function(Response $response) {
                return $response->getBodyAsText() === '{"message":"My test response"}';
            }));

        $router = new Router($exceptionHandler, $output);

        $router->post('/products', [TestController::class, 'index']);

        $request = $this->createMock(Request::class);

        $request->expects($this->once())
            ->method('uri')
            ->willReturn('/products?id=1&page=1');

        $request->expects($this->once())
            ->method('method')
            ->willReturn('POST');

        $router->handleRequest($request);
    }

    /**
     * @throws MockException
     */
    public function testExceptionInController()
    {
        $expectedResponse = $this->createStub(Response::class);

        $exceptionHandler = $this->createMock(ExceptionHandler::class);

        $exceptionHandler->expects($this->once())
            ->method('handle')
            ->willReturn($expectedResponse);

        $output = $this->createMock(Output::class);

        $output->expects($this->once())
            ->method('sendResponse')
            ->with($expectedResponse);

        $router = new Router($exceptionHandler, $output);

        $router->get('/products', [TestController::class, 'indexWithException']);

        $request = $this->createMock(Request::class);

        $request->expects($this->once())
            ->method('uri')
            ->willReturn('/products?id=1&page=1');

        $request->expects($this->once())
            ->method('method')
            ->willReturn('GET');

        $router->handleRequest($request);
    }

}

class TestController
{

    public function index() : Response
    {
        return new JsonResponse([
            'message' => 'My test response',
        ]);
    }

    /**
     * @throws Exception
     */
    public function indexWithException() : Response
    {
        throw new Exception('My exception');
    }

}