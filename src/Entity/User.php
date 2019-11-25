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
}
