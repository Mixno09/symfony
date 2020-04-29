<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class ProductTitle extends Constraint
{
    public $message = 'Значение должно быть более 5 символов и менее 255';

    public function validatedBy()
    {
        return ProductTitleValidator::class;
    }
}