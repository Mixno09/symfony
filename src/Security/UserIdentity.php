<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class UserIdentity implements UserInterface
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string[]
     */
    private $roles = [];

    /**
     * @var string The hashed password
     */
    private $password;

    /**
     * UserIdentity constructor.
     * @param string $username
     * @param string[] $roles
     * @param string $password
     */
    public function __construct(string $username, array $roles, string $password)
    {
        $this->username = $username;
        $this->roles = $roles;
        $this->password = $password;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public static function fromUser(User $user): self
    {
        return new self($user->email, $user->getRoles(), $user->getPassword());
    }
}
