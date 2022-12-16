<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Opinion;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class OpinionFixtures extends Fixture implements DependentFixtureInterface
{
    public const NB_DECISION = 5;
    public const NB_USER = 5;
    public const NB_OPINION = 5;



    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($userId = 0; $userId < self::NB_USER; $userId++) {
            for ($historyId = 0; $historyId < self::NB_DECISION; $historyId++) {
                for ($opinionId = 0; $opinionId < self::NB_OPINION; $opinionId++) {
                    $opinion = new Opinion();

                    $opinion->setIsLike($faker->boolean());
                    $opinion->setCreatedAt($faker->dateTime());
                    $this->addReference('opinion_' . $opinionId, $opinion);

                    $opinion->setUser($this->getReference('user_' . $faker->numberBetween(0, 5)));
                    $opinion->setDecision($this->getReference('decision_' . $faker->numberBetween(0, 5)));


                    $manager->persist($opinion);
                }

                $manager->flush();
            }
        }
    }

    public function getDependencies()
    {
        return [
           UserFixtures::class,
           DecisionFixtures::class,

         ];
    }
}
