<?php

namespace Hugojose39\Beedrillpay\Tests\Requests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Hugojose39\Beedrillpay\Requests\CreateCardTokenRequest;
use Hugojose39\Beedrillpay\Tests\TestCase;

class CreateCardTokenRequestTest extends TestCase
{
    private readonly CreateCardTokenRequest $request;
    private readonly string $endpoint;

    public function setUp(): void
    {
        parent::setUp();

        $this->endpoint = 'https://apisandbox.cieloecommerce.cielo.com.br';
    }

    public function testSuccess(): void
    {
        $handler  = new MockHandler();

        $handler->append(
            new Response(
                status: 200,
                body: file_get_contents(__DIR__ . '/Mock/CreateCardTokenSuccess.json')
            )
        );

        $this->request = new CreateCardTokenRequest(new Client(['handler' => $handler]), $this->endpoint);

        $response = $this->request->create([
            'Card' => [
                'CustomerName' => 'Comprador Teste Cielo',
                'CardNumber' => '4532117080573700',
                'Holder' => 'Comprador T Cielo',
                'ExpirationDate' => '12/2030',
                'Brand' => 'Visa',
            ]
        ]);

        $this->assertEquals('db62dc71-d07b-4745-9969-42697b988ccb', $response['CardToken']);
        $this->assertNotNull($response);
    }

    public function testError(): void
    {
        $handler  = new MockHandler();

        $handler->append(
            new Response(
                status: 401,
                body: file_get_contents(__DIR__ . '/Mock/CreateCardTokenFailure.json')
            )
        );

        $this->request = new CreateCardTokenRequest(new Client(['handler' => $handler]), $this->endpoint);

        $response = $this->request->create([
            'Card' => [
                'CustomerName' => 'Comprador Teste Cielo',
                'CardNumber' => '4532117080573700',
                'Holder' => 'Comprador T Cielo',
                'ExpirationDate' => '12/2030',
                'Brand' => 'Visa',
            ]
        ]);

        $this->assertEquals('MerchantId is required', $response[0]['Message']);
    }
}
