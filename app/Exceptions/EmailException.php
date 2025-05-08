<?php
declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

class EmailException extends RuntimeException
{
    const int INVALID_CONFIG = 1001;

    const int API_FAILED = 1002;

    const int INVALID_ATTACHMENT = 1003;
}