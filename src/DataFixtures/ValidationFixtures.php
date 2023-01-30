<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Validation;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ValidationFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        // $faker = Factory::create();

        // for ($userId = 0; $userId < self::NB_USER; $userId++) {
        //     for ($decisionId = 0; $decisionId < self::NB_DECISION; $decisionId++) {
        //         for ($validationId = 0; $validationId < self::NB_VALIDATION; $validationId++) {
        //             $validation = new Validation();
        //             $validation->setComment($faker->text(55));
        //             $validation->setIsApproved(false);
        //             $validation->setUser($this->getReference('user_' . $userId));
        //             $validation->setDecision($this->getReference('decision_' .
        //                 (string)(($userId * self::NB_DECISION) + $decisionId)));
        //             $validation->setCreatedAt(new DateTime('now'));
        //             $this->addReference('validation_' . (string)(((($userId * self::NB_DECISION) +
        //                 $decisionId) * self::NB_VALIDATION) + $validationId), $validation);
        //             $manager->persist($validation);
        //         }
        //     }
        // }
        // $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            DecisionFixtures::class,
        ];
    }
}
