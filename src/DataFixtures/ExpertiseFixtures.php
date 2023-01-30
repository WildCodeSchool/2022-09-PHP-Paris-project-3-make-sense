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
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($userId = 0; $userId < UserFixtures::NB_USER; $userId++) {
            // for ($decisionId = 0; $decisionId < self::NB_DECISION; $decisionId++) {
                // for ($opinionId = 0; $opinionId < self::NB_EXPERTISE; $opinionId++) {
                foreach (Department::DEPARTMENTS as $key => $departmentName) {
                    $expertise = new Expertise();
                    $expertise->setIsExpert($faker->boolean());
                    $expertise->setUser($this->getReference('user_' . $userId));
                    // $key = array_rand(Department::DEPARTMENTS);
                    $expertise->setDepartment($this->getReference('department_' . $key));
                    $manager->persist($expertise);
                }
                $manager->flush();
            // }
        }
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            DepartmentFixtures::class,
        ];
    }
}
