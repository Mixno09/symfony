<?php

declare(strict_types=1);

namespace App\Fixture;

use App\Repository\PDOProductRepository;
use PDO;
use Sylius\Bundle\FixturesBundle\Listener\AbstractListener;
use Sylius\Bundle\FixturesBundle\Listener\BeforeSuiteListenerInterface;
use Sylius\Bundle\FixturesBundle\Listener\SuiteEvent;

class ReviewPurgerListener extends AbstractListener implements BeforeSuiteListenerInterface
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * UserPurgerListener constructor.
     * @param \PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getName(): string
    {
        return 'review_purger';
    }

    public function beforeSuite(SuiteEvent $suiteEvent, array $options): void
    {
        $sql = 'DELETE FROM ' . PDOProductRepository::REVIEW_TABLE;
        $this->pdo->exec($sql);
    }
}