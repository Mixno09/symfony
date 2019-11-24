<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class User
{
    /**
     * @var int
     */
    public $id = 0;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Email(mode="strict")
     */
    // TODO  сделать проверку на уникальный email
    public $email = '';

    /**
     * @var string The hashed password
     */
    public $password = '';
}
