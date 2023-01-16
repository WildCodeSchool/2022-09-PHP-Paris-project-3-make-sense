<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

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
            $user->setImagename('photo.jpg');
            $user->setPhone(0654454545);
            $user->setRoles(['ROLE_USER']);
            $user->setemail($faker->email());
            $user->setCreatedAt(new DateTimeImmutable('now'));
            $user->setUpdatedAt(new DateTimeImmutable('now'));
            $this->addReference('user_' . $userId, $user);
            $user->setPlainPassword('password');
            $manager->persist($user);
        }
        $manager->flush();
    }
}
