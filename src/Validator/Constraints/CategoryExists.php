<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class CategoryExists extends Constraint
{
    public string $invalidUuidMessage = 'Значение должно быть Uuid';

    public string $notExistsMessage = 'Категория с id = {{ id }} не существует';

    public function validatedBy()
    {
        return CategoryExistsValidator::class;
    }
}
