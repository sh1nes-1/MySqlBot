<?php

namespace Core\Http;

use PHPUnit\Framework\MockObject\Exception;
use Sh1ne\MySqlBot\Core\Http\Middleware;
use Sh1ne\MySqlBot\Core\Http\MiddlewareList;
use PHPUnit\Framework\TestCase;
use Sh1ne\MySqlBot\Core\Http\Request;
use Sh1ne\MySqlBot\Core\Http\RequestHandler;
use Sh1ne\MySqlBot\Core\Http\Response;

/**
 * @covers MiddlewareList
 */
class MiddlewareListTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testHandleWithoutMiddleware()
    {
        $expectedResponse = $this->createStub(Response::class);

        $finalRequestHandler = $this->mockRequestHandler($expectedResponse);

        $request = $this->createMock(Request::class);

        $request->expects($this->never())
            ->method('uri');

        $middlewareList = new MiddlewareList();
        $response = $middlewareList->handle($request, $finalRequestHandler);

        $this->assertSame($response, $expectedResponse);
    }

    /**
     * @throws Exception
     */
    public function testHandleWithNotMatchingMiddleware()
    {
        $expectedResponse = $this->createStub(Response::class);
        $finalRequestHandler = $this->mockRequestHandler($expectedResponse);

        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('uri')
            ->willReturn('/api/v1/test1');

        $middleware = $this->createMock(Middleware::class);

        $middleware->expects($this->never())
            ->method('handle');

        $middlewareList = new MiddlewareList();
        $middlewareList->add('/api/v1/test2', $middleware);
        $response = $middlewareList->handle($request, $finalRequestHandler);

        $this->assertSame($response, $expectedResponse);
    }

    /**
     * @throws Exception
     */
    public function testHandleWithOneMatchingMiddleware()
    {
        $request = $this->createMock(Request::class);

        $request->expects($this->once())
            ->method('uri')
            ->willReturn('/api/v1/test');

        $expectedResponse = $this->createStub(Response::class);

        $finalRequestHandler = $this->mockRequestHandler($expectedResponse);

        $middleware = $this->mockMiddleware($finalRequestHandler, $request);

        $middlewareList = new MiddlewareList();
        $middlewareList->add('/api/v1/test', $middleware);
        $response = $middlewareList->handle($request, $finalRequestHandler);

        $this->assertSame($response, $expectedResponse);
    }

    /**
     * @throws Exception
     */
    public function testHandleWithTwoMatchingMiddleware()
    {
        $request = $this->createMock(Request::class);

        $request->expects($this->exactly(2))
            ->method('uri')
            ->willReturn('/api/v1/test');

        $expectedResponse = $this->createStub(Response::class);
        $finalRequestHandler = $this->mockRequestHandler($expectedResponse);
        $middleware2 = $this->mockMiddleware($finalRequestHandler, $request);
        $middleware1 = $this->mockMiddleware($middleware2, $request);

        $middlewareList = new MiddlewareList();
        $middlewareList->add('/api/v1/test', $middleware1);
        $middlewareList->add('/api/v1/test', $middleware2);

        $response = $middlewareList->handle($request, $finalRequestHandler);

        $this->assertSame($response, $expectedResponse);
    }

    /**
     * @throws Exception
     */
    private function mockRequestHandler(Response $expectedResponse) : RequestHandler
    {
        $finalRequestHandler = $this->createMock(RequestHandler::class);

        $finalRequestHandler->expects($this->once())
            ->method('handle')
            ->willReturn($expectedResponse);

        return $finalRequestHandler;
    }

    /**
     * @throws Exception
     */
    private function mockMiddleware(RequestHandler $finalRequestHandler, Request $request) : Middleware
    {
        $middleware = $this->createMock(Middleware::class);

        $middleware->expects($this->once())
            ->method('setNext')
            ->with($finalRequestHandler);

        $middleware->expects($this->once())
            ->method('handle')
            ->with($request)
            ->willReturnCallback(fn(Request $request) => $finalRequestHandler->handle($request));

        return $middleware;
    }

}