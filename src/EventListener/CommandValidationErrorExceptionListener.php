<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Messenger\Exception\CommandValidationErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class CommandValidationErrorExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (! $exception instanceof CommandValidationErrorException) {
            return;
        }

        $errors = $exception->getErrors();
        $response = new JsonResponse([
            'type' => 'validation_error',
            'errors' => $this->errorsToArray($errors),
        ]);

        $event->allowCustomResponseCode();
        $event->setResponse($response);
    }

    private function errorsToArray(ConstraintViolationListInterface $errors): array
    {
        $json = [];
        foreach ($errors as $error) {
            /** @var \Symfony\Component\Validator\ConstraintViolationInterface $error */
            $key = $error->getPropertyPath();
            $message = $error->getMessage();
            $json[$key][] = $message;
        }
        return $json;
    }
}