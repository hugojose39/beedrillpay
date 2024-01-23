<?php

namespace Hugojose39\Beedrillpay\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Hugojose39\Beedrillpay\Beedrillpay;
use Hugojose39\Beedrillpay\BeedrillpayServiceProvider;

class BeedrillpayTest extends TestCase
{
    public Beedrillpay $gateway;
    public string $endpoint;

    public function setUp(): void
    {
        parent::setUp();

        $this->gateway = BeedrillpayServiceProvider::configure('teste1234', 'teste1234');
        $this->endpoint = 'https://apisandbox.cieloecommerce.cielo.com.br';
    }

    public function testAutomaticCapture(): void
    {
        $handler  = new MockHandler();

        $handler->append(
            new Response(
                status: 200,
                body: file_get_contents(__DIR__ . '/Requests/Mock/AuthorizeSuccess.json')
            )
        );

        $this->gateway->__construct(new Client(['handler' => $handler]), $this->endpoint);
        $response = $this->gateway->automaticCapture();

        $this->assertEquals('2014111706', $response['MerchantOrderId']);
        $this->assertEquals('a5f3181d-c2e2-4df9-a5b4-d8f6edf6bd51', $response['Payment']['PaymentId']);
        $this->assertNotNull($response);
    }

    public function testLaterCapture(): void
    {
        $handler  = new MockHandler();

        $handler->append(
            new Response(
                status: 200,
                body: file_get_contents(__DIR__ . '/Requests/Mock/AuthorizeSuccess.json')
            )
        );

        $this->gateway->__construct(new Client(['handler' => $handler]), $this->endpoint);
        $response = $this->gateway->laterCapture();

        $this->assertEquals('2014111706', $response['MerchantOrderId']);
        $this->assertEquals('a5f3181d-c2e2-4df9-a5b4-d8f6edf6bd51', $response['Payment']['PaymentId']);
        $this->assertNotNull($response);
    }

    public function testCreateCardToken(): void
    {
        $handler  = new MockHandler();

        $handler->append(
            new Response(
                status: 200,
                body: file_get_contents(__DIR__ . '/Requests/Mock/CreateCardTokenSuccess.json')
            )
        );

        $this->gateway->__construct(new Client(['handler' => $handler]), $this->endpoint);
        $response = $this->gateway->cardToken();

        $this->assertEquals('db62dc71-d07b-4745-9969-42697b988ccb', $response['CardToken']);
        $this->assertNotNull($response);
    }

    public function testCapture(): void
    {
        $handler  = new MockHandler();

        $handler->append(
            new Response(
                status: 200,
                body: file_get_contents(__DIR__ . '/Requests/Mock/CaptureSuccess.json')
            )
        );

        $this->gateway->__construct(new Client(['handler' => $handler]), $this->endpoint);
        $response = $this->gateway->capture();

        $this->assertEquals(2, $response['Status']);
        $this->assertEquals('Operation Successful', $response['ReturnMessage']);
        $this->assertNotNull($response);
    }
}
