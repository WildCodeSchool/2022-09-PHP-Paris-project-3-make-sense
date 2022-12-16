<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Decision;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class DecisionFixtures extends Fixture implements DependentFixtureInterface
{
    public const NB_DECISION = 25;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($decision_id = 0; $decision_id < self::NB_DECISION; $decision_id++) {
            $decision = new Decision();
            $decision->setTitle($faker->text(5));
            $decision->setDescription($faker->text(15));
            $decision->setBenefits($faker->text(15));
            $decision->setLikeThreshold(1);
            $decision->setCreatedAt(new \DateTime('now'));
            $decision->setOwner($this->getReference('user_' . '1'));
            $this->addReference('decision_' . $decision_id, $decision);
            $manager->persist($decision);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
}
