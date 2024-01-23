<?php

namespace Hugojose39\Beedrillpay\Exception;

use Exception;

class AuthenticationException extends Exception implements BedrillpayException
{
    public function __construct($message = "Authentication variables were not defined", $code = 403, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}