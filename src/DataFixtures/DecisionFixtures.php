<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Decision;
use Faker;
use DateTimeImmutable;

class DecisionFixtures extends Fixture implements DependentFixtureInterface
{
    public const USER = 5;
    public const DECISION = 25;

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($j = 0; $j < self::DECISION; $j++) {
            $decision = new Decision();
            $decision->setTitle($faker->words(6, true));
            $decision->setDescription($faker->text(25));
            $decision->setImpacts($faker->text(25));
            $decision->setBenefits($faker->text(25));
            $decision->setRisks($faker->text(25));
            $key = array_rand(DECISION::STATUSES);
            $decision->setStatus($key);
            $decision->setLikeThreshold($faker->numberBetween(30, 70));
            $decision->setCreatedAt(new DateTimeImmutable('now'));
            $decision->setEndAt(new DateTimeImmutable('02/23/2023'));
            $decision->setOwner($this->getReference('user_' . rand(0, UserFixtures::NB_USER - 1)));
            $this->addReference('decision_' . $j, $decision);
            $manager->persist($decision);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
