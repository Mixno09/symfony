<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;

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
     * @var \DateTimeImmutable
     */
    public $date;

    /**
     * Comment constructor.
     * @param \App\Entity\User $author
     * @param string $text
     * @throws \Exception
     */
    public function __construct(User $author, string $text)
    {
        $this->author = $author;
        $this->text = $text;
        $this->date = new DateTimeImmutable();
    }

    public function update(string $text): void
    {
        $this->text = $text;
    }
}