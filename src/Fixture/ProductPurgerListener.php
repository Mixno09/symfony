<?php

declare(strict_types=1);

namespace App\Fixture;

use Sylius\Bundle\FixturesBundle\Listener\AbstractListener;
use Sylius\Bundle\FixturesBundle\Listener\BeforeSuiteListenerInterface;
use Sylius\Bundle\FixturesBundle\Listener\SuiteEvent;
use Symfony\Component\Filesystem\Filesystem;

class ProductPurgerListener extends AbstractListener implements BeforeSuiteListenerInterface
{
    /**
     * @var string
     */
    private $fileDirectory;

    /**
     * @var string
     */
    private $fileDatabase;

    /**
     * ProductPurgerListener constructor.
     * @param string $fileDirectory
     * @param string $fileDatabase
     */
    public function __construct(string $fileDirectory, string $fileDatabase)
    {
        $this->fileDirectory = $fileDirectory;
        $this->fileDatabase = $fileDatabase;
    }

    public function getName(): string
    {
        return 'product_purger';
    }

    public function beforeSuite(SuiteEvent $suiteEvent, array $options): void
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->fileDirectory);
        $filesystem->remove($this->fileDatabase);
    }
}