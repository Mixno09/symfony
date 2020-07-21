<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class ProductNotExists extends Constraint
{
    public string $message = 'Продукт с таким ID уже сеществует';

    public function validatedBy()
    {
        return ProductNotExistsValidator::class;
    }
}