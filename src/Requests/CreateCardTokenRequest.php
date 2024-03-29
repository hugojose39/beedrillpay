<?php

namespace Hugojose39\Beedrillpay\Requests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\RequestOptions;
use Hugojose39\Beedrillpay\Exception\BadMethodCallException;

class CreateCardTokenRequest
{
    public function __construct(
        private Client $client,
        private string $endpoint,
    )
    {
    }

    public function create(array $parameters): array
    {
        try {
            $request = $this->client->request('POST', $this->endpoint.'/1/card', [
                RequestOptions::JSON => $parameters['Card'],
            ]);

            return json_decode($request->getBody(), true);
        } catch (ClientException $exception) {
            throw new BadMethodCallException($exception, $exception->getCode(), $exception);
        } catch (ConnectException $exception) {
            throw new BadMethodCallException($exception, $exception->getCode(), $exception);
        }
    }
}