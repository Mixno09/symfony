<?php

declare(strict_types=1);

namespace App\ArgumentResolver;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class UuidValueResolver implements ArgumentValueResolverInterface
{
    /**
     * @inheritDoc
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return is_a($argument->getType(), UuidInterface::class, true);
    }

    /**
     * @inheritDoc
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $argumentValue = $request->attributes->get($argument->getName());
        if (! is_string($argumentValue)) {
            throw new NotFoundHttpException();
        }

        if (! Uuid::isValid($argumentValue)) {
            throw new NotFoundHttpException();
        }

        yield Uuid::fromString($argumentValue);
    }
}