<?php

namespace Hugojose39\Beedrillpay\Exception;

use Exception;

class UnprocessableEntityException extends Exception implements BedrillpayException
{
    public function __construct($message = "Unprocessable Entity", $code = 422, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}