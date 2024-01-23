<?php

namespace Hugojose39\Beedrillpay\Tests;

use Hugojose39\Beedrillpay\Beedrillpay;
use Hugojose39\Beedrillpay\BeedrillpayServiceProvider;
use Hugojose39\Beedrillpay\Exception\AuthenticationException;

class BeedrillpayServiceProividerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testSuccess(): void
    {
        $this->assertInstanceOf(Beedrillpay::class, BeedrillpayServiceProvider::configure('teste1234', 'teste1234'));
    }

    public function testError(): void
    {
        $this->expectException(AuthenticationException::class);

        BeedrillpayServiceProvider::configure('', '');
    }
}
