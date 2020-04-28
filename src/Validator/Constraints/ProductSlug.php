<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class ProductSlug extends Constraint
{
    public $message =
        'Значение должно содержать только латинские символы в нижнем регистре, цифры и "-".' .
        'Не должно начинаться и заканчиваться с "-" и не должен быть два раза подряд';

    public function validatedBy()
    {
        return ProductSlugValidator::class;
    }
}
