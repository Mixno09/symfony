<?php

declare(strict_types=1);

namespace App\ArgumentResolver;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class UuidValueResolver implements ArgumentValueResolverInterface
{
    /**
     * @inheritDoc
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return ($argument->getType() === UuidInterface::class);
    }

    /**
     * @inheritDoc
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $argumentValue = $request->attributes->get($argument->getName());
        if (! is_string($argumentValue)) {
            throw new InvalidArgumentException('Аргумент не является строкой');
        }

        if (! Uuid::isValid($argumentValue)) {
            throw new InvalidArgumentException('Аргумент не является Uuid');
        }

        yield Uuid::fromString($argumentValue);
    }
}