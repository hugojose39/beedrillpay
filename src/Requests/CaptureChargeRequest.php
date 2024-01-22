<?php

namespace Hugojose39\Beedrillpay\Requests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\RequestOptions;
use Hugojose39\Beedrillpay\Exception\BadMethodCallException;
use Hugojose39\Beedrillpay\Exception\UnauthorizedException;
use Hugojose39\Beedrillpay\Exception\UnprocessableEntityException;

class CaptureChargeRequest
{
    const CAPTURE_ENDPOINT = 'https://api.pagar.me/core/v5/charges/%s/capture';

    public function __construct(
        private Client $client,
    )
    {
    }

    public function capture(string $authentication, string $chargeId, ?string $amount): array
    {
        try {
            $request = $this->client->request('POST', sprintf(self::CAPTURE_ENDPOINT, $chargeId), [
                RequestOptions::HEADERS => [
                    'Authorization' => $authentication,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                RequestOptions::JSON => ['amount' => $amount],
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
}