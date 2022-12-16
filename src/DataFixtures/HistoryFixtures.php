<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\History;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class HistoryFixtures extends Fixture implements DependentFixtureInterface
{
    public const NB_HISTORY = 2;
    public const NB_DECISION = 5;


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($decisionId = 0; $decisionId < self::NB_DECISION; $decisionId++) {
            for ($historyId = 0; $historyId < self::NB_HISTORY; $historyId++) {
                    $history = new History();

                    $history->setStatus($faker->text(15));
                    $this->addReference('history_' . $historyId, $history);

                    $history->setStartedAt($this->getReference('started_at_' . $faker->numberBetween(0, 5)));
                    $history->setDecision($this->getReference('decision_' . $faker->numberBetween(0, 5)));
                    $history->setEndedAt($this->getReference('ended_at_' . $faker->numberBetween(0, 5)));



                    $manager->persist($history);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            DecisionFixtures::class,
         ];
    }
}
