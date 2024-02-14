<?php

namespace Middleware;

use PHPUnit\Framework\MockObject\Exception;
use Sh1ne\MySqlBot\Core\Clock;
use Sh1ne\MySqlBot\Core\Config\Env;
use Sh1ne\MySqlBot\Core\Http\Request;
use Sh1ne\MySqlBot\Core\Http\RequestHandler;
use Sh1ne\MySqlBot\Core\Http\ResponseFactory;
use Sh1ne\MySqlBot\Middleware\SlackAuthorization;
use PHPUnit\Framework\TestCase;

class SlackAuthorizationTest extends TestCase
{

    protected function tearDown() : void
    {
        parent::tearDown();
        Clock::setTestNow(null);
    }

    /**
     * @throws Exception
     */
    public function testHandleSuccessful()
    {
        $signature = 'v0=a2114d57b48eac39b9ad189dd8316235a7b4a8d21a10bd27519666489c69b503';
        $timestamp = '1531420618';
        $rawBody = 'token=xyzz0WbapA4vBCDEFasx0q6G&team_id=T1DC2JH3J&team_domain=testteamnow&channel_id=G8PSS9T3V&channel_name=foobar&user_id=U2CERLKJA&user_name=roadrunner&command=%2Fwebhook-collect&text=&response_url=https%3A%2F%2Fhooks.slack.com%2Fcommands%2FT1DC2JH3J%2F397700885554%2F96rGlfmibIGlgcZRskXaIFfN&trigger_id=398738663015.47445629121.803a0bc887a14d10d2c447fce8b6703c';

        Clock::setTestNow(1531420619);
        Env::set('SLACK_SIGNING_SECRET', '8f742231b10e8888abcd99yyyzzz85a5');

        $request = $this->createMock(Request::class);

        $request->expects($this->exactly(2))
            ->method('header')
            ->willReturnOnConsecutiveCalls($timestamp, $signature);

        $request->expects($this->once())
            ->method('rawBody')
            ->willReturn($rawBody);

        $responseFactory = $this->createMock(ResponseFactory::class);

        $responseFactory->expects($this->never())
            ->method('json');

        $next = $this->createMock(RequestHandler::class);

        $next->expects($this->once())
            ->method('handle')
            ->with($request);

        $middleware = new SlackAuthorization($responseFactory);
        $middleware->setNext($next);
        $middleware->handle($request);
    }

    /**
     * @throws Exception
     */
    public function testHandleExpiredTimestamp()
    {
        $signature = 'v0=a2114d57b48eac39b9ad189dd8316235a7b4a8d21a10bd27519666489c69b503';
        $timestamp = '1531420618';

        Clock::setTestNow(1531420919);
        Env::set('SLACK_SIGNING_SECRET', '8f742231b10e8888abcd99yyyzzz85a5');

        $request = $this->createMock(Request::class);

        $request->expects($this->exactly(2))
            ->method('header')
            ->willReturnOnConsecutiveCalls($timestamp, $signature);

        $responseFactory = $this->createMock(ResponseFactory::class);

        $responseFactory->expects($this->once())
            ->method('json')
            ->with([
                'message' => 'Unauthorized',
                'reason' => 'Missing or invalid timestamp header',
            ], 403);

        $next = $this->createMock(RequestHandler::class);

        $next->expects($this->never())
            ->method('handle');

        $middleware = new SlackAuthorization($responseFactory);
        $middleware->setNext($next);
        $middleware->handle($request);
    }

    /**
     * @throws Exception
     */
    public function testHandleInvalidSignature()
    {
        $signature = 'SOME_BAD_SIGNATURE';
        $timestamp = '1531420618';
        $rawBody = 'token=xyzz0WbapA4vBCDEFasx0q6G&team_id=T1DC2JH3J&team_domain=testteamnow&channel_id=G8PSS9T3V&channel_name=foobar&user_id=U2CERLKJA&user_name=roadrunner&command=%2Fwebhook-collect&text=&response_url=https%3A%2F%2Fhooks.slack.com%2Fcommands%2FT1DC2JH3J%2F397700885554%2F96rGlfmibIGlgcZRskXaIFfN&trigger_id=398738663015.47445629121.803a0bc887a14d10d2c447fce8b6703c';

        Clock::setTestNow(1531420619);
        Env::set('SLACK_SIGNING_SECRET', '8f742231b10e8888abcd99yyyzzz85a5');

        $request = $this->createMock(Request::class);

        $request->expects($this->exactly(2))
            ->method('header')
            ->willReturnOnConsecutiveCalls($timestamp, $signature);

        $request->expects($this->once())
            ->method('rawBody')
            ->willReturn($rawBody);

        $responseFactory = $this->createMock(ResponseFactory::class);

        $responseFactory->expects($this->once())
            ->method('json')
            ->with([
                'message' => 'Unauthorized',
                'reason' => 'Invalid signature',
            ], 403);

        $next = $this->createMock(RequestHandler::class);

        $next->expects($this->never())
            ->method('handle');

        $middleware = new SlackAuthorization($responseFactory);
        $middleware->setNext($next);
        $middleware->handle($request);
    }

}
