<?php

namespace Middleware;

use PHPUnit\Framework\MockObject\Exception;
use Sh1ne\MySqlBot\Core\Http\Request;
use Sh1ne\MySqlBot\Core\Http\Response;
use Sh1ne\MySqlBot\Core\Http\ResponseFactory;
use Sh1ne\MySqlBot\Core\ServiceContainer;
use Sh1ne\MySqlBot\Middleware\SlackVerificationMiddleware;
use PHPUnit\Framework\TestCase;

class SlackVerificationMiddlewareTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testHandle()
    {
        $request = $this->createMock(Request::class);

        $request->expects($this->exactly(2))
            ->method('input')
            ->willReturnOnConsecutiveCalls('url_verification', 'my_test_challenge');

        $expectedResponse = $this->createStub(Response::class);

        $responseFactory = $this->createMock(ResponseFactory::class);

        $responseFactory->expects($this->once())
            ->method('json')
            ->with([
                'challenge' => 'my_test_challenge',
            ])
            ->willReturn($expectedResponse);

        ServiceContainer::instance()->singletonByInstance(ResponseFactory::class, $responseFactory);

        $slackVerificationMiddleware = new SlackVerificationMiddleware();

        $response = $slackVerificationMiddleware->handle($request);

        $this->assertSame($expectedResponse, $response);
    }

}
