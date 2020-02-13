<?php

declare(strict_types=1);

namespace App\Tests;

use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use PHPUnit\Framework\TestSuite;

class FunctionalTestListener implements TestListener
{
    use TestListenerDefaultImplementation;

    public function startTestSuite(TestSuite $suite): void
    {
        if (! $this->checkSuite($suite)) {
            return;
        }

        passthru(sprintf(
            'APP_ENV=%s php "%s/../bin/console" doctrine:schema:drop --full-database --force',
            $_ENV['APP_ENV'],
            __DIR__
        ));

        passthru(sprintf(
            'APP_ENV=%s php "%s/../bin/console" doctrine:migration:migrate --no-interaction',
            $_ENV['APP_ENV'],
            __DIR__
        ));
    }

    public function endTestSuite(TestSuite $suite): void
    {
        if (! $this->checkSuite($suite)) {
            return;
        }

        passthru(sprintf(
            'APP_ENV=%s php "%s/../bin/console" doctrine:schema:drop --full-database --force',
            $_ENV['APP_ENV'],
            __DIR__
        ));
    }

    private function checkSuite(TestSuite $suite): bool
    {
        return ($suite->getName() === 'Functional');
    }
}