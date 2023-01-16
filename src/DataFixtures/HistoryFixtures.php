<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\History;
use App\Entity\Decision;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use DateTimeImmutable;

class HistoryFixtures extends Fixture implements DependentFixtureInterface
{
    public const NB_HISTORY = 5;
    public const NB_DECISION = 5;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $historyRef = 0;
        for ($decisionId = 0; $decisionId < self::NB_DECISION; $decisionId++) {
            for ($historyId = 0; $historyId < self::NB_HISTORY; $historyId++) {
                $history = new History();
                $historyRef++;
                $history->setStartedAt((new DateTimeImmutable('now')));
                $history->setEndedAt((new DateTimeImmutable('now')));
                $history->setcreatedAt((new DateTimeImmutable('now')));
                $history->setDecision($this->getReference('decision_' . $faker->numberBetween(0, 24)));
                $history->setStatus(Decision::STATUS[$historyId]);
                $this->addReference('history_' . $historyRef, $history);
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
