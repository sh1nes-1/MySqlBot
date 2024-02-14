<?php

use PHPUnit\Framework\MockObject\Exception as MockException;
use PHPUnit\Framework\TestCase;
use Sh1ne\MySqlBot\Core\Config\Env;
use Sh1ne\MySqlBot\Core\Http\Response;
use Sh1ne\MySqlBot\Core\Http\ResponseFactory;
use Sh1ne\MySqlBot\ExceptionHandler;

class ExceptionHandlerTest extends TestCase
{

    /**
     * @throws MockException
     * @dataProvider provideHandleData
     */
    public function testHandle(bool $appDebug, callable $jsonCallback) : void
    {
        Env::set('APP_DEBUG', $appDebug);

        $expectedResponse = $this->createStub(Response::class);

        $responseFactory = $this->mockResponseFactory($expectedResponse, $jsonCallback);

        $handler = new ExceptionHandler($responseFactory);

        $response = $handler->handle(new Exception());

        $this->assertEquals($response, $expectedResponse);
    }

    public static function provideHandleData() : array
    {
        return [
            [
                'appDebug' => true,
                'jsonCallback' => function(array $response) {
                    self::assertArrayHasKey('file', $response);
                    self::assertArrayHasKey('line', $response);
                    self::assertArrayHasKey('message', $response);
                    self::assertArrayHasKey('code', $response);
                    self::assertArrayHasKey('trace', $response);

                    return true;
                },
            ],
            [
                'appDebug' => false,
                'jsonCallback' => function(array $response) {
                    self::assertArrayHasKey('message', $response);
                    self::assertArrayNotHasKey('file', $response);
                    self::assertArrayNotHasKey('line', $response);
                    self::assertArrayNotHasKey('code', $response);
                    self::assertArrayNotHasKey('trace', $response);

                    return true;
                },
            ],
        ];
    }

    /**
     * @throws MockException
     */
    private function mockResponseFactory(Response $expectedResponse, callable $jsonCallback) : ResponseFactory
    {
        $responseFactory = $this->createMock(ResponseFactory::class);

        $responseFactory->method('json')
            ->willReturn($expectedResponse);

        $responseFactory->expects($this->once())
            ->method('json')
            ->with($this->callback($jsonCallback));

        return $responseFactory;
    }

}
