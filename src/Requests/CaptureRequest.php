<?php

namespace Hugojose39\Beedrillpay\Requests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\RequestOptions;
use Hugojose39\Beedrillpay\Exception\BadMethodCallException;

class CaptureRequest
{
    public function __construct(
        private Client $client,
        private string $endpoint,
    )
    {
    }

    public function capture(string $paymentId, ?int $amount = null): array
    {
        try {
            $capture = is_null($amount) ? '/capture' : "/capture?amount={$amount}";

            $request = $this->client->request('PUT', "{$this->endpoint}/1/sales/{$paymentId}".$capture);

            return json_decode($request->getBody(), true);
        } catch (ClientException $exception) {
            throw new BadMethodCallException($exception, $exception->getCode(), $exception);
        } catch (ConnectException $exception) {
            throw new BadMethodCallException($exception, $exception->getCode(), $exception);
        }
    }
}