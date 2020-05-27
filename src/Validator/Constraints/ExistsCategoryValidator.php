<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class ExistsCategoryValidator extends ConstraintValidator
{
    private CategoryRepository $repository;

    /**
     * ProductSlugValidator constructor.
     * @param \App\Repository\CategoryRepository $repository
     */
    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        if (! $constraint instanceof ExistsCategory) {
            throw new UnexpectedTypeException($constraint, ExistsCategory::class);
        }

        if ($value === null || $value === '') {
            return;
        }

        if (! is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (! Uuid::isValid($value)) {
            $this->context->buildViolation($constraint->invalidUuidMessage)->addViolation();
            return;
        }

        $id = Uuid::fromString($value);
        $category = $this->repository->find($id);

        if (! $category instanceof Category) {
            $this->context->buildViolation($constraint->notExistsMessage)
                ->setParameter('{{ id }}', $id->toString())
                ->addViolation();
        }
    }
}