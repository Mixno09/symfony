<?php

declare(strict_type=1);

namespace App\UseCase\Test;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class Handler implements MessageHandlerInterface
{
    public function __invoke(Command $command): void
    {
        file_put_contents(__DIR__ . '/test.txt', __METHOD__ . ':' . __LINE__ . "\n", FILE_APPEND);
    }
}