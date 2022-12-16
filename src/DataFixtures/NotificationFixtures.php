<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Notification;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class NotificationFixtures extends Fixture implements DependentFixtureInterface
{
    public const NB_NOTIFICATION = 2;

    public const NB_USER = 5;
    public const NB_DECISION = 5;

    public function load(ObjectManager $manager): Void
    {
        $faker = Factory::create();

        for ($user_id = 0; $user_id < self::NB_USER; $user_id++) {
            for ($decision_id = 0; $decision_id < self::NB_DECISION; $decision_id++) {
                for ($notification_id = 0; $notification_id < self::NB_NOTIFICATION; $notification_id++) {
                    $notification = new Notification();

                    $notification->setCreatedAt($faker->dateTime());
                    $notification->setMessage($faker->paragraphs(3, true));
                    $this->addReference('notification_' . $notification_id, $notification);

                    $notification->setUser($this->getReference('user_' . $faker->numberBetween(0, 5)));
                    $notification->setDecision($this->getReference('decision_' . $faker->numberBetween(0, 5)));


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