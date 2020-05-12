<?php

declare(strict_type=1);

namespace App\UseCase\CreateUser;

final class Command
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $email;

}