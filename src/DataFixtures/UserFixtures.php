<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\EntityListener\UserListener;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private Generator $faker;
    public const NB_USER = 5;
    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for ($userId = 0; $userId < self::NB_USER; $userId++) {
            $user = new User();
            $user->setFirstname($this->faker->firstName());
            $user->setLastname($this->faker->lastName());
           // $user->setPassword('12345678');
            $user->setPicture('photo.jpg');
            $user->setPhone(0654454545);
            $user->setemail($this->faker->email());
            $user->setCreatedAt(new \DateTimeImmutable('now'));
            $user->setUpdatedAt(new \DateTimeImmutable('now'));
            $this->addReference('user_'.$userId, $user);
            $user->setRoles(['ROLE_USER']);
            $user->setPlainPassword('password');

            $manager->persist($user);
        }
        $manager->flush();
    }
}
