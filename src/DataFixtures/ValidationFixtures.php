<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Validation;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ValidationFixtures extends Fixture implements DependentFixtureInterface
{
    public const NB_USER = 5;
    public const NB_DECISION = 5;
    public const NB_CONTENT = 5;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($user_id = 0; $user_id < self::NB_USER; $user_id++) {
            for ($decision_id = 0; $decision_id < self::NB_DECISION; $decision_id++) {
                for ($content_id = 0; $content_id < self::NB_CONTENT; $content_id++) {
                    $validation = new Validation();
                    $validation->setComment($faker->text(55));
                    $validation->setIsApproved(false);
                    $validation->setUser($this->getReference('user_' . $user_id));
                    $validation->setDecision($this->getReference('decision_' . ($user_id*self::NB_DECISION)+$decision_id));
                    $validation->setCreatedAt(new \DateTime('now'));
                    $this->addReference('validation_' . ((($user_id*self::NB_DECISION)+$decision_id)*self::NB_CONTENT)+$content_id, $validation);
                    $manager->persist($validation);
                }
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            DecisionFixtures::class,
        ];
    }
}
