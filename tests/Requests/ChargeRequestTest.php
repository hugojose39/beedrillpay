<?php

namespace Hugojose39\Beedrillpay\Tests\Requests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Hugojose39\Beedrillpay\Requests\ChargeRequest;
use Hugojose39\Beedrillpay\Tests\TestCase;

class ChargeRequestTest extends TestCase
{
    public ChargeRequest $request;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testAuthorizeSuccess()
    {
        $handler  = new MockHandler();

        $handler->append(
            new Response(
                status: 200,
                body: file_get_contents(__DIR__ . '/Mock/AuthorizeSuccess.json')
            )
        );

        $this->request = new ChargeRequest(new Client(['handler' => $handler]));
        $response = $this->request->authorize('ApiKey1234', []);

        $this->assertEquals('or_28dN9w7CLU79kDjL', $response['id']);
        $this->assertEquals(2990, $response['amount']);
        $this->assertNotNull($response);
    }

    public function testAuthorizeError()
    {
        $handler  = new MockHandler();

        $handler->append(
            new Response(
                status: 401,
                body: file_get_contents(__DIR__ . '/Mock/AuthorizeFailure.json')
            )
        );

        $this->request = new ChargeRequest(new Client(['handler' => $handler]));
        $response = $this->request->authorize('ApiKey1234', []);

        $this->assertEquals('Authorization has been denied for this request.', $response['message']);
    }

    public function testNotAuthorizeSuccess()
    {
        $handler  = new MockHandler();

        $handler->append(
            new Response(
                status: 200,
                body: file_get_contents(__DIR__ . '/Mock/NotAuthorizeSuccess.json')
            )
        );

        $this->request = new ChargeRequest(new Client(['handler' => $handler]));
        $response = $this->request->common('ApiKey1234', []);

        $this->assertEquals('or_28dN9w7CLU79kDjL', $response['id']);
        $this->assertEquals(2990, $response['amount']);
        $this->assertNotNull($response);
    }

    public function testNotAuthorizeError()
    {
        $handler  = new MockHandler();

        $handler->append(
            new Response(
                status: 401,
                body: file_get_contents(__DIR__ . '/Mock/NotAuthorizeFailure.json')
            )
        );
        $this->request = new ChargeRequest(new Client(['handler' => $handler]));
        $response = $this->request->common('ApiKey1234', []);

        $this->assertEquals('Authorization has been denied for this request.', $response['message']);
    }

    public function testPreAuthorizeSuccess()
    {
        $handler  = new MockHandler();

        $handler->append(
            new Response(
                status: 200,
                body: file_get_contents(__DIR__ . '/Mock/PreAuthorizeSuccess.json')
            )
        );

        $this->request = new ChargeRequest(new Client(['handler' => $handler]));
        $response = $this->request->preAuthorize('ApiKey1234', []);

        $this->assertEquals('or_28dN9w7CLU79kDjL', $response['id']);
        $this->assertEquals(2990, $response['amount']);
        $this->assertNotNull($response);
    }

    public function testPreAuthorizeError()
    {
        $handler  = new MockHandler();

        $handler->append(
            new Response(
                status: 401,
                body: file_get_contents(__DIR__ . '/Mock/PreAuthorizeFailure.json')
            )
        );

        $this->request = new ChargeRequest(new Client(['handler' => $handler]));
        $response = $this->request->preAuthorize('ApiKey1234', []);

        $this->assertEquals('Authorization has been denied for this request.', $response['message']);
    }
}
