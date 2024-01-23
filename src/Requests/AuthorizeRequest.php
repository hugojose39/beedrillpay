<?php

namespace Hugojose39\Beedrillpay\Requests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\RequestOptions;
use Hugojose39\Beedrillpay\Exception\BadMethodCallException;

class AuthorizeRequest
{
    public function __construct(
        private Client $client,
        private string $endpoint,
    ) {
    }

    public function authorize(array $parameters, bool $capture = false): array
    {
        try {
            $request = $this->client->request('POST', $this->endpoint . '/1/sales', [
                RequestOptions::JSON => [
                    'MerchantOrderId' => $parameters['MerchantOrderId'] ?? null,
                    'Customer' => $parameters['Customer'] ?? [],
                    'Payment' => array_merge(
                        $parameters['Payment'] ?? [],
                        ['Capture' => $capture],
                    ),
                ],
            ]);

            return json_decode($request->getBody(), true);
        } catch (ClientException $exception) {
            throw new BadMethodCallException($exception, $exception->getCode(), $exception);
        } catch (ConnectException $exception) {
            throw new BadMethodCallException($exception, $exception->getCode(), $exception);
        }
    }

    public function automaticCapture(array $parameters): array
    {
        return $this->authorize($parameters, true);
    }

    public function laterCapture(array $parameters): array
    {
        return $this->authorize($parameters);
    }
}
