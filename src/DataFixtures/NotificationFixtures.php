<?php

namespace App\DataFixtures;

use Faker\Factory;
use DateTimeImmutable;
use App\Entity\Notification;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class NotificationFixtures extends Fixture implements DependentFixtureInterface
{
    public const NB_NOTIFICATION = 2;
    public const NB_USER = 5;
    public const NB_DECISION = 5;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($userId = 0; $userId < self::NB_USER; $userId++) {
            for ($decisionId = 0; $decisionId < self::NB_DECISION; $decisionId++) {
                for ($notificationId = 0; $notificationId < self::NB_NOTIFICATION; $notificationId++) {
                    $notification = new Notification();
                    $notification->setCreatedAt(new DateTimeImmutable('now'));
                    $notification->setMessage($faker->paragraphs(3, true));
                    $notification->setUser($this->getReference('user_' . $faker->numberBetween(0, 4)));
                    $notification->setDecision($this->getReference('decision_' . $faker->numberBetween(0, 24)));
                    $manager->persist($notification);
                }
            }
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
