<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Expertise;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Department as Department;

class ExpertiseFixtures extends Fixture implements DependentFixtureInterface
{
    public const NB_DECISION = 5;
    public const NB_USER = 5;
    public const NB_EXPERTISE = 5;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($userId = 0; $userId < self::NB_USER; $userId++) {
            for ($decisionId = 0; $decisionId < self::NB_DECISION; $decisionId++) {
                for ($opinionId = 0; $opinionId < self::NB_EXPERTISE; $opinionId++) {
                    $expertise = new Expertise();
                    $expertise->setIsExpert($faker->boolean());
                    $expertise->setUser($this->getReference('user_' . $faker->numberBetween(0, 4)));
                    $expertise->setDepartment($this->getReference('department_' . Department::departments[rand(0, 7)]));
                    $manager->persist($expertise);
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
