<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\History;
use App\Entity\Decision;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use DateTimeImmutable;

class HistoryFixtures extends Fixture implements DependentFixtureInterface
{
    public const NB_HISTORY = 5;

    public function load(ObjectManager $manager): void
    {
        $history = new History();
        $history->setStartedAt((new DateTimeImmutable('now')));
        $history->setEndedAt((new DateTimeImmutable('now')));
        $history->setcreatedAt((new DateTimeImmutable('now')));
        $history->setDecision($this->getReference('decision_0'));
        $history->setStatus(Decision::STATUS_DRAFT);
        $manager->persist($history);
        $manager->flush();

        $history = new History();
        $history->setStartedAt((new DateTimeImmutable('now')));
        $history->setEndedAt((new DateTimeImmutable('now')));
        $history->setcreatedAt((new DateTimeImmutable('now')));
        $history->setDecision($this->getReference('decision_1'));
        $history->setStatus(Decision::STATUS_DRAFT);
        $manager->persist($history);
        $manager->flush();

        $history = new History();
        $history->setStartedAt((new DateTimeImmutable('now')));
        $history->setEndedAt((new DateTimeImmutable('now')));
        $history->setcreatedAt((new DateTimeImmutable('now')));
        $history->setDecision($this->getReference('decision_1'));
        $history->setStatus(Decision::STATUS_CURRENT);
        $manager->persist($history);
        $manager->flush();

        $history = new History();
        $history->setStartedAt((new DateTimeImmutable('now')));
        $history->setEndedAt((new DateTimeImmutable('now')));
        $history->setcreatedAt((new DateTimeImmutable('now')));
        $history->setDecision($this->getReference('decision_2'));
        $history->setStatus(Decision::STATUS_DRAFT);
        $manager->persist($history);
        $manager->flush();

        $history = new History();
        $history->setStartedAt((new DateTimeImmutable('now')));
        $history->setEndedAt((new DateTimeImmutable('now')));
        $history->setcreatedAt((new DateTimeImmutable('now')));
        $history->setDecision($this->getReference('decision_2'));
        $history->setStatus(Decision::STATUS_CURRENT);
        $manager->persist($history);
        $manager->flush();

        $history = new History();
        $history->setStartedAt((new DateTimeImmutable('now')));
        $history->setEndedAt((new DateTimeImmutable('now')));
        $history->setcreatedAt((new DateTimeImmutable('now')));
        $history->setDecision($this->getReference('decision_2'));
        $history->setStatus(Decision::STATUS_FIRST_DECISION);
        $manager->persist($history);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            DecisionFixtures::class,
        ];
    }
}
