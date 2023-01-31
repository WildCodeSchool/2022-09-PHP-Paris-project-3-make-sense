<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const NB_USER = 5;

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($userId = 0; $userId < self::NB_USER; $userId++) {
            $user = new User();
            $firstname = $faker->firstName();
            $lastname = $faker->lastName();
            $user->setFirstname($firstname);
            $user->setLastname($lastname);

            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                '123456'
            );
            $user->setPassword($hashedPassword);

            $user->setPoster('user.jpg');
            $user->setPhone(0654454545);

            if (!$userId) {
                $user->setemail('admin@gmail.com');
                $user->setRoles(['ROLE_ADMIN']);
            } else {
                $user->setemail($firstname . '.' . $lastname . '@gmail.com');
                $user->setRoles(['ROLE_USER']);
            }

            $user->setCreatedAt(new DateTimeImmutable('now'));
            $user->setUpdatedAt(new DateTimeImmutable('now'));

            $this->addReference('user_' . $userId, $user);
            // $user->setPlainPassword('password');
            $manager->persist($user);
        }
        $manager->flush();
    }
}
