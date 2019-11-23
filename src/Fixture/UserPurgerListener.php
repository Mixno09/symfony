<?php

declare(strict_types=1);

namespace App\Fixture;

use Sylius\Bundle\FixturesBundle\Listener\AbstractListener;
use Sylius\Bundle\FixturesBundle\Listener\BeforeSuiteListenerInterface;
use Sylius\Bundle\FixturesBundle\Listener\SuiteEvent;
use Symfony\Component\Filesystem\Filesystem;

class UserPurgerListener extends AbstractListener implements BeforeSuiteListenerInterface
{
    /**
     * @var string
     */
    private $fileDatabase;

    /**
     * UserPurgerListener constructor.
     * @param string $fileDatabase
     */
    public function __construct(string $fileDatabase)
    {
        $this->fileDatabase = $fileDatabase;
    }

    public function getName(): string
    {
        return 'user_purger';
    }

    public function beforeSuite(SuiteEvent $suiteEvent, array $options): void
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->fileDatabase);
    }
}