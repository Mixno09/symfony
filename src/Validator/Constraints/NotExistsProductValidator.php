<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class NotExistsProductValidator extends ConstraintValidator
{
    private ProductRepository $repository;

    /**
     * ProductUuidValidator constructor.
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
        if (! $constraint instanceof NotExistsProduct) {
            throw new UnexpectedTypeException($constraint, NotExistsProduct::class);
        }

        if (! is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $product = $this->repository->find($value);

        if ($product instanceof Product) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}