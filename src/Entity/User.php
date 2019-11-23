<?php

declare(strict_types=1);

namespace App\Entity;

class User
{
    /**
     * @var int
     */
    public $id = 0;

    /**
     * @var string
     */
    public $email = '';

    /**
     * @var string The hashed password
     */
    public $password = '';
}
