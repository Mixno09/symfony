<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Entity\Product;
use App\Entity\ValueObject\Slug as ValueObject;
use App\Repository\ProductRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class SlugValidator extends ConstraintValidator
{
    private ProductRepository $repository;

    /**
     * ProductSlugValidator constructor.
     * @param \App\Repository\ProductRepository $repository
     */
    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        if (! $constraint instanceof Slug) {
            throw new UnexpectedTypeException($constraint, Slug::class);
        }

        if (! is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (! ValueObject::test($value)) {
            $this->context->buildViolation($constraint->invalidFormatMessage)->addViolation();
            return;
        }

        $product = $this->repository->findOneBy(['slug.value' => $value]);

        if ($product instanceof Product) {
            $this->context->buildViolation($constraint->notUniqueMessage)->addViolation();
        }
    }
}