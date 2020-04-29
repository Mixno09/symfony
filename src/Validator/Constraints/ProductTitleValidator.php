<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Entity\ValueObject\ProductTitle as ValueObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class ProductTitleValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        if (! $constraint instanceof ProductTitle) {
            throw new UnexpectedTypeException($constraint, ProductTitle::class);
        }

        if (! is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (! ValueObject::test($value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}