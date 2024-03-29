<?php

namespace Untek\Framework\Rpc\Domain\Exceptions;

use Exception;
use Throwable;
use Untek\Framework\Rpc\Domain\Enums\RpcErrorCodeEnum;

class InvalidCharacterException extends ParseErrorException
{

    public function __construct($message = 'Invalid character for encoding', $code = RpcErrorCodeEnum::PARSE_ERROR_INVALID_CHARACTER_FOR_ENCODING, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
