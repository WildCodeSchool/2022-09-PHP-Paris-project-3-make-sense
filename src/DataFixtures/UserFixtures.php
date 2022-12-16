<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends Fixture 
{
    public const NB_USER = 5;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($user_id = 0; $user_id < self::NB_USER; $user_id++) {

            $user = new User();
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setPassword('12345678');
            $user->setemail($faker->email());
            $user->setCreatedAt(new \DateTimeImmutable('now'));
            $user->setUpdatedAt(new \DateTimeImmutable('now'));
            $this->addReference('user_' . $user_id, $user);
            $manager->persist($user);
        }

        $manager->flush();
    }

    // public function getDependencies(): array
    // {
    //     return [
    //         // DecisionFixtures::class,
    //     ];
    // }
}
