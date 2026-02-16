<?php

declare(strict_types=1);

namespace App\Exceptions\Auth;

use Exception;

final class InvalidCredentialsException extends Exception
{
    public function __construct()
    {
        parent::__construct('Credenciais inválidas.', 401);
    }
}
