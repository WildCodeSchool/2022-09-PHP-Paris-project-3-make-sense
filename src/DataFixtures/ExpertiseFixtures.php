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
    public const NB_EXPERTISE = 5;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $keys = array_keys(Department::DEPARTMENTS);

        for ($userId = 0; $userId < UserFixtures::NB_USER; $userId++) {
            for ($depId = 0; $depId < 8; $depId++) {
                // for ($opinionId = 0; $opinionId < self::NB_EXPERTISE; $opinionId++) {
                    $expertise = new Expertise();
                    $expertise->setIsExpert($faker->boolean());
                    $expertise->setUser($this->getReference('user_' . $userId));
                    $expertise->setDepartment($this->getReference('department_' . $keys[$depId]));
                    $manager->persist($expertise);
                // }
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
