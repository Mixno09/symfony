<?php

declare(strict_types=1);

namespace App\Fixture;

use App\Repository\PDOUserRepository;
use PDO;
use Sylius\Bundle\FixturesBundle\Listener\AbstractListener;
use Sylius\Bundle\FixturesBundle\Listener\BeforeSuiteListenerInterface;
use Sylius\Bundle\FixturesBundle\Listener\SuiteEvent;
use Symfony\Component\Filesystem\Filesystem;

class UserPurgerListener extends AbstractListener implements BeforeSuiteListenerInterface
{
    /**
     * @var \PDO
     */
    private $pdo;
    /**
     * @var string
     */
    private $fileDatabase;

    /**
     * UserPurgerListener constructor.
     * @param string $fileDatabase
     * @param \PDO $pdo
     */
    public function __construct(string $fileDatabase, PDO $pdo)
    {
        $this->fileDatabase = $fileDatabase;
        $this->pdo = $pdo;
    }

    public function getName(): string
    {
        return 'user_purger';
    }

    public function beforeSuite(SuiteEvent $suiteEvent, array $options): void
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->fileDatabase);
        $sql = 'TRUNCATE ' . PDOUserRepository::TABLE;
        $this->pdo->exec($sql);
    }
}