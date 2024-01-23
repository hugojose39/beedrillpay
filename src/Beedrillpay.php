<?php

namespace Hugojose39\Beedrillpay;

use GuzzleHttp\Client;
use Hugojose39\Beedrillpay\Requests\AuthorizeRequest;
use Hugojose39\Beedrillpay\Requests\CaptureRequest;
use Hugojose39\Beedrillpay\Requests\CreateCardTokenRequest;

class Beedrillpay
{
    public function __construct(
        private Client $client,
        private string $endpoint,
    ) {
    }

    public function automaticCapture(array $parameters = [])
    {
        $request = new AuthorizeRequest($this->client, $this->endpoint);

        return $request->automaticCapture($parameters);
    }

    public function laterCapture(array $parameters = [])
    {
        $request = new AuthorizeRequest($this->client, $this->endpoint);

        return $request->laterCapture($parameters);
    }

    public function cardToken(array $parameters = [])
    {
        $request = new CreateCardTokenRequest($this->client, $this->endpoint);

        return $request->create($parameters);
    }

    public function capture(array $parameters = [])
    {
        $request = new CaptureRequest($this->client, $this->endpoint);

        return $request->capture($parameters['paymentId'] ?? '', $parameters['amount'] ?? null);
    }
}
