<?php

declare(strict_types=1);

namespace App\Messenger\Exception;

use RuntimeException;
use Symfony\Component\Messenger\Exception\UnrecoverableExceptionInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

final class CommandValidationErrorException extends RuntimeException implements UnrecoverableExceptionInterface
{
    private ConstraintViolationListInterface $errors;

    public function __construct(ConstraintViolationListInterface $errors, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->errors = $errors;
    }

    /**
     * @return \Symfony\Component\Validator\ConstraintViolationListInterface
     */
    public function getErrors(): ConstraintViolationListInterface
    {
        return $this->errors;
    }
}