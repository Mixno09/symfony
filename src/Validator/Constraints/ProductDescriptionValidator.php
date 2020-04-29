<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Entity\ValueObject\ProductDescription as ValueObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class ProductDescriptionValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        if (! $constraint instanceof ProductDescription) {
            throw new UnexpectedTypeException($constraint, ProductDescription::class);
        }

        if (! is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (! ValueObject::test($value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}