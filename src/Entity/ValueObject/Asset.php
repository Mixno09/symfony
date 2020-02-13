<?php

declare(strict_types=1);

namespace App\Entity\ValueObject;

interface Asset
{
    public function getPath(): string;
    public function getPackageName(): ?string;
}