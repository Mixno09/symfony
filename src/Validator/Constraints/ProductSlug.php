<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class ProductSlug extends Constraint
{
    public $message = 'Значение указанно неверно';

    public function validatedBy()
    {
        return ProductSlugValidator::class;
    }
}
