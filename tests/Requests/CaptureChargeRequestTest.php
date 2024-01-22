<?php

namespace Hugojose39\Beedrillpay\Tests\Requests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Hugojose39\Beedrillpay\Requests\CaptureChargeRequest;
use Hugojose39\Beedrillpay\Tests\TestCase;

class CaptureChargeRequestTest extends TestCase
{
    public CaptureChargeRequest $request;

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
                body: file_get_contents(__DIR__ . '/Mock/CaptureChargeSuccess.json')
            )
        );

        $this->request = new CaptureChargeRequest(new Client(['handler' => $handler]));
        $response = $this->request->capture('ApiKey1234', '1234', '1490');

        $this->assertEquals('ch_6NXoYXyiNfP3A54l', $response['id']);
        $this->assertEquals(1490, $response['amount']);
        $this->assertNotNull($response);
    }

    public function testError()
    {
        $handler  = new MockHandler();

        $handler->append(
            new Response(
                status: 401,
                body: file_get_contents(__DIR__ . '/Mock/CaptureChargeFailure.json')
            )
        );

        $this->request = new CaptureChargeRequest(new Client(['handler' => $handler]));
        $response = $this->request->capture('ApiKey1234', '1234', '1490');

        $this->assertEquals('Authorization has been denied for this request.', $response['message']);
    }
}
