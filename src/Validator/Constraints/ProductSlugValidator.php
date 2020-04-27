<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Entity\ValueObject\ProductSlug as SlugVO;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class ProductSlugValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        if (! $constraint instanceof ProductSlug) {
            throw new UnexpectedTypeException($constraint, ProductSlug::class);
        }

        if (! is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (! SlugVO::test($value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}