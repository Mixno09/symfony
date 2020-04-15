<?php


namespace App\UseCase\Test;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class Middleware implements MiddlewareInterface
{

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        file_put_contents(__DIR__ . '/test.txt', __METHOD__ . ':' . __LINE__ . "\n", FILE_APPEND);

        $envelope = $stack->next()->handle($envelope, $stack);

        file_put_contents(__DIR__ . '/test.txt', __METHOD__ . ':' . __LINE__ . "\n", FILE_APPEND);
        return $envelope;
    }
}