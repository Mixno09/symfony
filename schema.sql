DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS products;

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

CREATE TABLE products
(
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title       VARCHAR(255) NOT NULL,
    description TEXT         NOT NULL,
    image       TEXT         NOT NULL,
    PRIMARY KEY (id)
) ENGINE InnoDB;

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




