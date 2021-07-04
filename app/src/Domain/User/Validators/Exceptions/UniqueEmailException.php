<?php

namespace App\Domain\User\Validators\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

final class UniqueEmailException extends ValidationException
{
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'The given PESEL number exists in the database.',
        ],
    ];
}