<?php

namespace Hugojose39\Beedrillpay\Exception;

use Exception;

class UnauthorizedException extends Exception implements BedrillpayException
{
    public function __construct($message = "Unauthorized", $code = 401, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}