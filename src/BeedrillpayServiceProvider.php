<?php

namespace Hugojose39\Beedrillpay;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Hugojose39\Beedrillpay\Exception\AuthenticationException;

class BeedrillpayServiceProvider
{
    public static function configure(string $merchantId, string $merchantKey, bool $test = true)
    {
        $endpoint = $test
            ? 'https://apisandbox.cieloecommerce.cielo.com.br'
            : 'https://api.cieloecommerce.cielo.com.br';

        if (!$merchantId || !$merchantKey) {
            throw new AuthenticationException();
        }

        $guzzleClient = new Client([
            RequestOptions::HEADERS => [
                'MerchantId' => $merchantId,
                'MerchantKey' => $merchantKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            RequestOptions::TIMEOUT => 10,
        ]);

        return new Beedrillpay($guzzleClient, $endpoint);
    }
}
