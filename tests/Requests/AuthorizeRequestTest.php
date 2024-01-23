<?php

namespace Hugojose39\Beedrillpay\Tests\Requests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Hugojose39\Beedrillpay\Requests\AuthorizeRequest;
use Hugojose39\Beedrillpay\Tests\TestCase;

class AuthorizeRequestTest extends TestCase
{
    public AuthorizeRequest $request;
    private readonly string $endpoint;

    public function setUp(): void
    {
        parent::setUp();
        $this->endpoint = 'https://apisandbox.cieloecommerce.cielo.com.br';
    }

    public function testAutomaticCaptureSuccess(): void
    {
        $handler  = new MockHandler();

        $handler->append(
            new Response(
                status: 200,
                body: file_get_contents(__DIR__ . '/Mock/AuthorizeSuccess.json')
            )
        );

        $this->request = new AuthorizeRequest(new Client(['handler' => $handler]), $this->endpoint);
        $response = $this->request->automaticCapture([
            'MerchantOrderId' => '2014111706',
            'Customer' => [
                'Name' => 'Comprador Teste Boleto',
                'Identity' => '1234567890',
                'Address' => [
                    'Street' => 'Avenida Marechal Câmara',
                    'Number' => '160',
                    'Complement' => 'Sala 934',
                    'ZipCode' => '22750012',
                    'District' => 'Centro',
                    'City' => 'Rio de Janeiro',
                    'State' => 'RJ',
                    'Country' => 'BRA'
                ],
            ],
            'Payment' => [
                'Type' => 'Boleto',
                'Amount' => 15700,
                'Provider' => 'bradesco2',
                'Address' => 'Rua Teste',
                'BoletoNumber' => '123',
                'Assignor' => 'Empresa Teste',
                'Demonstrative' => 'Desmonstrative Teste',
                'ExpirationDate' => '5/1/2024',
                'Identification' => '11884926754',
                'Instructions' => 'Aceitar somente até a data de vencimento, após essa data juros de 1% dia.'
            ],
        ]);

        $this->assertEquals('2014111706', $response['MerchantOrderId']);
        $this->assertEquals('a5f3181d-c2e2-4df9-a5b4-d8f6edf6bd51', $response['Payment']['PaymentId']);
        $this->assertNotNull($response);
    }

    public function testAutomaticCaptureError(): void
    {
        $handler  = new MockHandler();

        $handler->append(
            new Response(
                status: 401,
                body: file_get_contents(__DIR__ . '/Mock/AuthorizeFailure.json')
            )
        );

        $this->request = new AuthorizeRequest(new Client(['handler' => $handler]), $this->endpoint);
        $response = $this->request->automaticCapture([]);

        $this->assertEquals('MerchantId is required', $response[0]['Message']);
    }

    public function testLaterCaptureSuccess(): void
    {
        $handler  = new MockHandler();
        $handler->append(
            new Response(
                status: 200,
                body: file_get_contents(__DIR__ . '/Mock/AuthorizeSuccess.json')
            )
        );

        $this->request = new AuthorizeRequest(new Client(['handler' => $handler]), $this->endpoint);
        $response = $this->request->laterCapture([
            'MerchantOrderId' => '2014111706',
            'Customer' => [
                'Name' => 'Comprador Teste Boleto',
                'Identity' => '1234567890',
                'Address' => [
                    'Street' => 'Avenida Marechal Câmara',
                    'Number' => '160',
                    'Complement' => 'Sala 934',
                    'ZipCode' => '22750012',
                    'District' => 'Centro',
                    'City' => 'Rio de Janeiro',
                    'State' => 'RJ',
                    'Country' => 'BRA'
                ],
            ],
            'Payment' => [
                'Type' => 'Boleto',
                'Amount' => 15700,
                'Provider' => 'bradesco2',
                'Address' => 'Rua Teste',
                'BoletoNumber' => '123',
                'Assignor' => 'Empresa Teste',
                'Demonstrative' => 'Desmonstrative Teste',
                'ExpirationDate' => '5/1/2024',
                'Identification' => '11884926754',
                'Instructions' => 'Aceitar somente até a data de vencimento, após essa data juros de 1% dia.'
            ],
        ]);

        $this->assertEquals('2014111706', $response['MerchantOrderId']);
        $this->assertEquals('a5f3181d-c2e2-4df9-a5b4-d8f6edf6bd51', $response['Payment']['PaymentId']);
        $this->assertNotNull($response);
    }

    public function testLaterCaptureError(): void
    {
        $handler  = new MockHandler();

        $handler->append(
            new Response(
                status: 401,
                body: file_get_contents(__DIR__ . '/Mock/AuthorizeFailure.json')
            )
        );

        $this->request = new AuthorizeRequest(new Client(['handler' => $handler]), $this->endpoint);
        $response = $this->request->laterCapture([]);

        $this->assertEquals('MerchantId is required', $response[0]['Message']);
    }
}
