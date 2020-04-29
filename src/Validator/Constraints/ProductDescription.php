<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class ProductDescription extends Constraint
{
    public $message = 'Значение должно содержать более 5 символов';

    public function validatedBy()
    {
        return ProductDescriptionValidator::class;
    }
}