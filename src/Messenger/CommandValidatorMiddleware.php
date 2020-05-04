<?php

declare(strict_types=1);

namespace App\Messenger;

use App\Messenger\Exception\CommandValidationErrorException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CommandValidatorMiddleware implements MiddlewareInterface
{
    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    private $validator;

    /**
     * ErrorsJsonMiddleware constructor.
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $command = $envelope->getMessage();
        $errors = $this->validator->validate($command);
        if (count($errors) > 0) {
            throw new CommandValidationErrorException($errors);
        }
        return $stack->next()->handle($envelope, $stack);
    }
}