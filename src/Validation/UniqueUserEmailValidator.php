<?php

declare(strict_types=1);

namespace App\Validation;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniqueUserEmailValidator extends ConstraintValidator
{
    /**
     * @var \App\Repository\UserRepositoryInterface
     */
    private $userRepository;

    /**
     * UniqueUserEmailValidator constructor.
     * @param \App\Repository\UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if (! $constraint instanceof UniqueUserEmail) {
            throw new UnexpectedTypeException($constraint, UniqueUserEmail::class);
        }

        if (! $value instanceof User) {
            throw new UnexpectedValueException($value, User::class);
        }

        $user = $this->userRepository->getByEmail($value->email);
        if (! $user instanceof User) {
            return;
        }

        if ($value->id === $user->id) {
            return;
        }

        $this->context->buildViolation('Такой email уже существует')
            ->atPath('email')
            ->addViolation();
    }
}