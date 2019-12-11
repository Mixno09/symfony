<?php

declare(strict_types=1);

namespace App\Fixture;

use App\Repository\PDOProductRepository;
use PDO;
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
     * @var \PDO
     */
    private $pdo;

    /**
     * ProductPurgerListener constructor.
     * @param string $fileDirectory
     * @param string $fileDatabase
     * @param \PDO $pdo
     */
    public function __construct(string $fileDirectory, string $fileDatabase, PDO $pdo)
    {
        $this->fileDirectory = $fileDirectory;
        $this->fileDatabase = $fileDatabase;
        $this->pdo = $pdo;
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
        $sql = 'TRUNCATE ' . PDOProductRepository::TABLE;
        $this->pdo->exec($sql);
    }
}