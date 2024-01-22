<?php

namespace Hugojose39\Beedrillpay\Tests\Requests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Hugojose39\Beedrillpay\Requests\CreateCardTokenRequest;
use Hugojose39\Beedrillpay\Tests\TestCase;

class CreateCardTokenRequestTest extends TestCase
{
    public CreateCardTokenRequest $request;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testSuccess()
    {
        $handler  = new MockHandler();

        $handler->append(
            new Response(
                status: 200,
                body: file_get_contents(__DIR__ . '/Mock/CreateCardTokenSuccess.json')
            )
        );

        $this->request = new CreateCardTokenRequest(new Client(['handler' => $handler]));
        $response = $this->request->create([
            'apiKey' => 'ApiKey1234',
            'type' => 'card',
            'card' => [
                'number' => '4000000000000010',
                'exp_month' => '1',
                'exp_year' => '30'
            ]
        ]);

        $this->assertEquals('token_zVw7rqvHEPH8XlbE', $response['id']);
        $this->assertEquals('Visa', $response['card']['brand']);
        $this->assertNotNull($response);
    }

    public function testError()
    {
        $handler  = new MockHandler();

        $handler->append(
            new Response(
                status: 401,
                body: file_get_contents(__DIR__ . '/Mock/CreateCardTokenFailure.json')
            )
        );

        $this->request = new CreateCardTokenRequest(new Client(['handler' => $handler]));
        $response = $this->request->create([
            'apiKey' => 'ApiKey1234',
            'type' => 'card',
            'card' => [
                'number' => '4000000000000010',
                'exp_month' => '1',
                'exp_year' => '30'
            ]
        ]);

        $this->assertEquals('Could not renew card.', $response['message']);
    }
}
