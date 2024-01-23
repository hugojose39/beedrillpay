<?php

namespace Hugojose39\Beedrillpay\Tests\Requests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Hugojose39\Beedrillpay\Requests\CaptureRequest;
use Hugojose39\Beedrillpay\Tests\TestCase;

class CaptureRequestTest extends TestCase
{
    public CaptureRequest $request;
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
                body: file_get_contents(__DIR__ . '/Mock/CaptureSuccess.json')
            )
        );

        $this->request = new CaptureRequest(new Client(['handler' => $handler]), $this->endpoint);
        $response = $this->request->capture('12346', 1490);

        $this->assertEquals(2, $response['Status']);
        $this->assertEquals('Operation Successful', $response['ReturnMessage']);
        $this->assertNotNull($response);
    }

    public function testError(): void
    {
        $handler  = new MockHandler();

        $handler->append(
            new Response(
                status: 401,
                body: file_get_contents(__DIR__ . '/Mock/CaptureFailure.json')
            )
        );

        $this->request = new CaptureRequest(new Client(['handler' => $handler]), $this->endpoint);
        $response = $this->request->capture('12346', 1490);

        $this->assertEquals('MerchantId is required', $response[0]['Message']);
    }
}
