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
use Symfony\Component\Security\Core\Security;

class ProductReviewVoter extends Voter
{
    private const UPDATE = 'update_review';
    private const CREATE = 'create_review';
    private const DELETE = 'delete_review';

    /**
     * @var \Symfony\Component\Security\Core\Security
     */
    private $security;

    /**
     * @var \App\Repository\UserRepositoryInterface
     */
    private $userRepository;

    /**
     * ProductReviewVoter constructor.
     * @param \App\Repository\UserRepositoryInterface $userRepository
     * @param \Symfony\Component\Security\Core\Security $security
     */
    public function __construct(UserRepositoryInterface $userRepository, Security $security)
    {
        $this->userRepository = $userRepository;
        $this->security = $security;
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
        if ($attribute === self::CREATE) {
            return $this->canCreate($subject, $token);
        }

        if ($attribute === self::UPDATE) {
            return $this->canUpdate($subject, $token);
        }

        if ($attribute === self::DELETE) {
            return $this->canDelete($subject, $token);
        }

        throw new LogicException('This code should not be reached!');
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

    private function canUpdate(Review $subject, TokenInterface $token): bool
    {
        if ($this->security->isGranted('ROLE_REVIEW_UPDATE')) {
            return true;
        }
        $user = $this->getUser($token);
        if (! $user instanceof User) {
            return false;
        }
        return $user->equals($subject->author);
    }

    private function canDelete(Review $subject, TokenInterface $token): bool
    {
        if ($this->security->isGranted('ROLE_REVIEW_DESTROY')) {
            return true;
        }
        $user = $this->getUser($token);
        if (! $user instanceof User) {
            return false;
        }
        return $user->equals($subject->author);
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