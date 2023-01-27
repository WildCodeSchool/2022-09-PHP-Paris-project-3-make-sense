<?php

namespace App\DataFixtures;

use Faker\Factory;
use DateTimeImmutable;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const NB_USER = 20;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($userId = 0; $userId < self::NB_USER; $userId++) {
            $user = new User();
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setPassword('12345678');
            $user->setPoster($faker->image());
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
