<?php

namespace Hugojose39\Beedrillpay\Requests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\RequestOptions;
use Hugojose39\Beedrillpay\Exception\BadMethodCallException;
use Hugojose39\Beedrillpay\Exception\UnauthorizedException;
use Hugojose39\Beedrillpay\Exception\UnprocessableEntityException;

class ChargeRequest
{
    const ORDER_ENDPOINT = 'https://api.pagar.me/core/v5/orders';

    public function __construct(
        private Client $client,
    ) {
    }

    public function common(string $authentication, array $parameters, string $operationType = 'auth_and_capture'): array
    {
        try {
            $request = $this->client->request('POST', self::ORDER_ENDPOINT, [
                RequestOptions::HEADERS => [
                    'Authorization' => $authentication,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                RequestOptions::JSON => [
                    'customer' => $parameters['customer'] ?? [],
                    'customer_id' => $parameters['customer_id'] ?? null,
                    'items' => $parameters['items'] ?? [],
                    'payments' => [
                        'payment_method' => 'credit_card',
                        'credit_card' => [
                            array_merge(
                                [
                                    'operation_type' => $operationType,
                                    'installments' => $parameters['installments'] ?? [],
                                    'statement_descriptor' => $parameters['statement_descriptor'] ?? [],
                                ],
                                $parameters['card'] ?? [],
                            ),
                        ],
                    ],
                ],
                RequestOptions::TIMEOUT => 10,
            ]);

            return json_decode($request->getBody(), true);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 401) {
                throw new UnauthorizedException($exception->getMessage(), $exception->getCode(), $exception);
            }

            if ($exception->getCode() === 422) {
                throw new UnprocessableEntityException($exception->getMessage(), $exception->getCode(), $exception);
            }

            throw new BadMethodCallException($exception, $exception->getCode(), $exception);
        } catch (ConnectException $exception) {
            throw new BadMethodCallException($exception, $exception->getCode(), $exception);
        }
    }

    public function authorize(string $authentication, array $parameters): array
    {
        return $this->common($authentication, $parameters, 'auth_only');
    }

    public function preAuthorize(string $authentication, array $parameters): array
    {
        return $this->common($authentication, $parameters, 'pre_auth');
    }
}
