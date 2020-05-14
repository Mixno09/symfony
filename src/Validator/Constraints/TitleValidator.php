<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Entity\ValueObject\Title as ValueObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class TitleValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        if (! $constraint instanceof Title) {
            throw new UnexpectedTypeException($constraint, Title::class);
        }

        if (! is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (! ValueObject::test($value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}