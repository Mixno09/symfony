<?php

declare(strict_types=1);

namespace App\Entity;

use App\Security\UserIdentity;
use App\Validation\UniqueUserEmail;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueUserEmail()
 */
class User
{
    /**
     * @var int
     */
    public $id = 0;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Email(mode="strict")
     */
    public $email = '';

    public $admin = false;

    /**
     * @var string[]
     */
    private $roles = [];

    /**
     * @var string The hashed password
     */
    private $password = '';

    public function updatePassword(string $password, UserPasswordEncoderInterface $passwordEncoder): void
    {
        $userIdentity = UserIdentity::fromUser($this);
        $password = $passwordEncoder->encodePassword($userIdentity, $password);
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        if ($this->admin) {
            $roles[] = 'ROLE_ADMIN';
        }
        return $roles;
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function equals(self $user): bool
    {
        return ($this->id === $user->id);
    }
}
