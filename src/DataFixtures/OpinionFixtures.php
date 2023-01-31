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

        for ($userId = 0; $userId < UserFixtures::NB_USER; $userId++) {
            for ($decisionId = 0; $decisionId < DecisionFixtures::NB_DECISION; $decisionId++) {
                // for ($opinionId = 0; $opinionId < self::NB_OPINION; $opinionId++) {
                    $opinion = new Opinion();
                    $opinion->setIsLike($faker->boolean());
                    $opinion->setUser($this->getReference('user_' . $userId));
                    $opinion->setDecision($this->getReference('decision_' . $decisionId));

                     $manager->persist($opinion);
                // }

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
