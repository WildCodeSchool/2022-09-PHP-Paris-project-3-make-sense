<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Faker\Factory;
use DateTimeImmutable;

class UserFixtures extends Fixture
{
    public const NB_USER = 5;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($userId = 0; $userId < self::NB_USER; $userId++) {
            $user = new User();
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setPassword('12345678');
            $user->setPicture('photo.jpg');
            $user->setPhone(0654454545);
            $user->setRoles(['ROLE_USER']);
            $user->setemail($faker->email());
            $user->setCreatedAt(new DateTimeImmutable('now'));
            $user->setUpdatedAt(new DateTimeImmutable('now'));
            $this->addReference('user_' . $userId, $user);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
