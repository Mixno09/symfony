<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200120184618 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Стартовая миграция';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql(
<<<SQL
CREATE TABLE users
(
    id       INT UNSIGNED NOT NULL AUTO_INCREMENT,
    email    VARCHAR(320) NOT NULL,
    admin    BOOLEAN      NOT NULL,
    roles    JSON         NOT NULL,
    password CHAR(60)     NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT UNIQUE (email)
) ENGINE InnoDB;
SQL
        );

        $this->addSql(
<<<SQL
CREATE TABLE products
(
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title       VARCHAR(255) NOT NULL,
    description TEXT         NOT NULL,
    image       TEXT         NOT NULL,
    PRIMARY KEY (id)
) ENGINE InnoDB;
SQL
        );

        $this->addSql(
<<<SQL
CREATE TABLE reviews
(
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    product_id INT UNSIGNED NOT NULL,
    user_id    INT UNSIGNED NOT NULL,
    `text`     TEXT         NOT NULL,
    `date`     DATETIME     NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (product_id) REFERENCES products (id),
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT UNIQUE (product_id, user_id)
) ENGINE InnoDB;
SQL
        );
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE reviews;');
        $this->addSql('DROP TABLE products;');
        $this->addSql('DROP TABLE users;');
    }
}
