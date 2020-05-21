<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class NotExistsProduct extends Constraint
{
    public string $message = 'Продукт с таким ID уже сеществует';

    public function validatedBy()
    {
        return NotExistsProductValidator::class;
    }
}