<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Opinion;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class OpinionFixtures extends Fixture implements DependentFixtureInterface
{
    public const NB_DECISION = 5;
    public const NB_USER = 5;
    public const NB_OPINION = 5;



    public function load(ObjectManager $manager): void
    {
    $faker = Factory::create();

    for ($user_id = 0; $user_id < self::NB_USER; $user_id++) {
        for ($history_id = 0; $history_id < self::NB_DECISION; $history_id++) {
            for ($opinion_id = 0; $opinion_id < self::NB_OPINION; $opinion_id++) {
                $opinion = new Opinion();

                $opinion->setIsLike($faker->boolean());
                $opinion->setCreatedAt($faker->dateTime());
                $this->addReference('opinion_' . $opinion_id, $opinion);

                $opinion->setUser($this->getReference('user_' . $faker->numberBetween(0, 5)));
                $opinion->setDecision($this->getReference('decision_' . $faker->numberBetween(0, 5)));


                $manager->persist($opinion);
            }

            $manager->flush();
        }
    }
}   
    

	public function getDependencies() 
    {
        return [
           UserFixtures::class,
           DecisionFixtures::class,

         ];
	}
}