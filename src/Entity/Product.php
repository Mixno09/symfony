<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Product
{
    /**
     * @var int
     */
    public $id = 0;
    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(max = 255)
     */
    public $title = '';
    /**
     * @var string
     * @Assert\NotBlank
     */
    public $description = '';
    /**
     * @var string
     */
    public $image = '';

}