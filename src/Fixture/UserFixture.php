<?php

declare(strict_types=1);

namespace App\Fixture;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use App\Security\UserIdentity;
use Faker\Factory;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends AbstractFixture
{
    /**
     * @var  \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var \App\Repository\UserRepositoryInterface
     */
    private $repository;

    /**
     * UserFixture constructor.
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
     * @param \App\Repository\UserRepositoryInterface $repository
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, UserRepositoryInterface $repository)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->repository = $repository;
    }

    public function load(array $options): void
    {
        $this->createUser('mixno09@mail.ru');

        $count = 10;
        $faker = Factory::create('ru_RU');
        while ($count > 0) {
            $email = $faker->unique()->safeEmail;
            $this->createUser($email);
            $count--;
        }
    }

    private function createUser(string $email): void
    {
        $user = new User();
        $user->email = $email;
        $userIdentity = UserIdentity::fromUser($user);
        $password = $this->passwordEncoder->encodePassword($userIdentity, 'password');
        $user->password = $password;
        $this->repository->save($user);
    }

    public function getName(): string
    {
        return 'user';
    }
}