<?php

namespace Hugojose39\Beedrillpay\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Hugojose39\Beedrillpay\Beedrillpay;

class BeedrillpayTest extends TestCase
{
    public Beedrillpay $gateway;

    public function setUp(): void
    {
        parent::setUp();

        $this->gateway = new Beedrillpay();
    }

    public function testAuthentication()
    {
        $authentication = $this->gateway->authentication('ApiKey1234');

        $this->assertNotNull($authentication);
        $this->assertEquals('Basic QXBpS2V5MTIzNDo=', $authentication);
    }

    public function testAuthorize()
    {
        $handler  = new MockHandler();

        $handler->append(
            new Response(
                status: 200,
                body: file_get_contents(__DIR__ . '/Requests/Mock/AuthorizeSuccess.json')
            )
        );

        $this->gateway->__construct(new Client(['handler' => $handler]));
        $response = $this->gateway->authorize();

        $this->assertEquals('or_28dN9w7CLU79kDjL', $response['id']);
        $this->assertEquals(2990, $response['amount']);
        $this->assertNotNull($response);
    }

    public function testNotAuthorize()
    {
        $handler  = new MockHandler();

        $handler->append(
            new Response(
                status: 200,
                body: file_get_contents(__DIR__ . '/Requests/Mock/NotAuthorizeSuccess.json')
            )
        );

        $this->gateway->__construct(new Client(['handler' => $handler]));
        $response = $this->gateway->notAuthorize();

        $this->assertEquals('or_28dN9w7CLU79kDjL', $response['id']);
        $this->assertEquals(2990, $response['amount']);
        $this->assertNotNull($response);
    }

    public function testPreAuthorize()
    {
        $handler  = new MockHandler();

        $handler->append(
            new Response(
                status: 200,
                body: file_get_contents(__DIR__ . '/Requests/Mock/PreAuthorizeSuccess.json')
            )
        );

        $this->gateway->__construct(new Client(['handler' => $handler]));
        $response = $this->gateway->preAuthorize();

        $this->assertEquals('or_28dN9w7CLU79kDjL', $response['id']);
        $this->assertEquals(2990, $response['amount']);
        $this->assertNotNull($response);
    }

    public function testCreateCardToken()
    {
        $handler  = new MockHandler();

        $handler->append(
            new Response(
                status: 200,
                body: file_get_contents(__DIR__ . '/Requests/Mock/CreateCardTokenSuccess.json')
            )
        );

        $this->gateway->__construct(new Client(['handler' => $handler]));
        $response = $this->gateway->cardToken();

        $this->assertEquals('token_zVw7rqvHEPH8XlbE', $response['id']);
        $this->assertEquals('Visa', $response['card']['brand']);
        $this->assertNotNull($response);
    }

    public function testCaptureChargeToken()
    {
        $handler  = new MockHandler();

        $handler->append(
            new Response(
                status: 200,
                body: file_get_contents(__DIR__ . '/Requests/Mock/CaptureChargeSuccess.json')
            )
        );

        $this->gateway->__construct(new Client(['handler' => $handler]));
        $response = $this->gateway->capture();

        $this->assertEquals('ch_6NXoYXyiNfP3A54l', $response['id']);
        $this->assertEquals(1490, $response['amount']);
        $this->assertNotNull($response);
    }
}
