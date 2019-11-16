<?php

declare(strict_types=1);

namespace App\Fixture;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
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
        $count = 10;
        $faker = Factory::create('ru_RU');
        $user = new User();
        $this->passwordEncoder->encodePassword($user, 'password');
        $password = $user->getPassword();
        while ($count > 0) {
            $user = new User();
            $user->setEmail($faker->safeEmail);
            $user->setPassword($password);
            $this->repository->save($user);
            $count--;
        }
    }

    public function getName(): string
    {
        return 'user';
    }
}