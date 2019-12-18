<?php

declare(strict_types=1);

namespace App\Entity;

class  Review
{
    /**
     * @var \App\Entity\User
     */
    public $author;
    /**
     * @var string
     */
    public $text;
    /**
     * @var int
     */
    public $id = 0;

    /**
     * Comment constructor.
     * @param \App\Entity\User $author
     * @param string $text
     */
    public function __construct(User $author, string $text)
    {
        $this->author = $author;
        $this->text = $text;
    }
}