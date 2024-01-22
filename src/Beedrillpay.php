<?php

namespace Hugojose39\Beedrillpay;

use GuzzleHttp\Client;
use Hugojose39\Beedrillpay\Requests\ChargeRequest;
use Hugojose39\Beedrillpay\Requests\CaptureChargeRequest;
use Hugojose39\Beedrillpay\Requests\CreateCardTokenRequest;

class Beedrillpay
{
    public function __construct(
        private Client $client = new Client(),
    )
    {
    }

    public function authentication(string $apiKey = ''): string {
        return 'Basic '.base64_encode($apiKey.':');
    }

    public function authorize(array $parameters = []) {
        $chargeRequest = new ChargeRequest($this->client);

        return $chargeRequest->authorize($this->authentication($parameters['apiKey'] ?? ''), $parameters ?? []);
    }

    public function cardToken(array $parameters = []) {
        $request = new CreateCardTokenRequest($this->client);

        return $request->create($parameters);
    }

    public function capture(array $parameters = []) {
        $captureChargeRequest = new CaptureChargeRequest($this->client);

        return $captureChargeRequest->capture(
            $this->authentication($parameters['apiKey'] ?? ''),
            $parameters['chargeId'] ?? '',
            $parameters['amount'] ?? null,
        );
    }

    public function notAuthorize(array $parameters = []) {
        $chargeRequest = new ChargeRequest($this->client);

        return $chargeRequest->common($this->authentication($parameters['apiKey'] ?? ''), $parameters ?? []);
    }

    public function preAuthorize(array $parameters = []) {
        $chargeRequest = new ChargeRequest($this->client);

        return $chargeRequest->preAuthorize($this->authentication($parameters['apiKey'] ?? ''), $parameters ?? []);
    }
}