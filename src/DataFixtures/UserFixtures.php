<?php

namespace App\DataFixtures;

use Faker\Factory;
use DateTimeImmutable;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const NB_USER = 5;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $user = new User();
        $user->setFirstname('admin');
        $user->setLastname('admin');
        $user->setPassword('12345678');
        $user->setPoster('user.jpg');
        $user->setPhone(0654454545);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setemail($faker->email());
        $user->setCreatedAt(new DateTimeImmutable('now'));
        $user->setUpdatedAt(new DateTimeImmutable('now'));
        $this->addReference('user_' . 0, $user);
        $user->setPlainPassword('password');
        $manager->persist($user);

        $manager->flush();


        for ($userId = 1; $userId < self::NB_USER; $userId++) {
            $user = new User();
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setPassword('12345678');
            $user->setPoster('user.jpg');
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
