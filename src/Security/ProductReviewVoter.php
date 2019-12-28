<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\Product;
use App\Entity\Review;
use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProductReviewVoter extends Voter
{
    private const UPDATE = 'update_review';
    private const CREATE = 'create_review';
    private const DELETE = 'delete_review';

    /**
     * @var \App\Repository\UserRepositoryInterface
     */
    private $userRepository;

    /**
     * ProductReviewVoter constructor.
     * @param \App\Repository\UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @inheritDoc
     */
    protected function supports($attribute, $subject)
    {
        if ($subject instanceof Review && in_array($attribute, [self::UPDATE, self::DELETE])) {
            return true;
        }
        if ($subject instanceof Product && $attribute === self::CREATE) {
            return true;
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if (in_array($attribute, [self::UPDATE, self::DELETE])) {
            return $this->canUpdateOrDelete($subject, $token);
        }

        if ($attribute === self::CREATE) {
            return $this->canCreate($subject, $token);
        }

        throw new LogicException('This code should not be reached!');
    }

    private function canUpdateOrDelete(Review $subject, TokenInterface $token): bool
    {
        $user = $this->getUser($token);
        if (! $user instanceof User) {
            return false;
        }
        return $user->equals($subject->author);
    }

    private function canCreate(Product $subject, TokenInterface $token): bool
    {
        $user = $this->getUser($token);
        if (! $user instanceof User) {
            return false;
        }

        $review = $subject->getUserReview($user);
        if ($review instanceof Review) {
            return false;
        }

        return true;
    }

    private function getUser(TokenInterface $token): ?User
    {
        $userIdentity = $token->getUser();
        if (! $userIdentity instanceof UserIdentity) {
            return null;
        }

        $email = $userIdentity->getUsername();
        $user = $this->userRepository->getByEmail($email);
        if (! $user instanceof User) {
            return null;
        }
        return $user;
    }
}