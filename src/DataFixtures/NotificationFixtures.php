<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Notification;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class NotificationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $faker = Factory::create();

        for ($userId = 0; $userId < UserFixtures::NB_USER; $userId++) {
            for ($decisionId = 0; $decisionId < 3; $decisionId++) {
                // for ($notificationId = 0; $notificationId < self::NB_NOTIFICATION; $notificationId++) {
                    $notification = new Notification();
                    $notification->setUser($this->getReference('user_' . $userId));
                    $notification->setDecision($this->getReference('decision_' . $decisionId));
                    $notification->setRead($this->getReference('decision_' . $decisionId));
                    $manager->persist($notification);
                }
            // }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            DecisionFixtures::class,

        ];
    }
}
