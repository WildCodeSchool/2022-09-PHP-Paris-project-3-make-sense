<?php

namespace App\DataFixtures;

use App\Entity\Department as Department;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class DepartmentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();
        foreach (Department::DEPARTMENTS as $departments) {
            $department = new Department();
            $department->setName($departments);
            for ($i = 0; $i < 8; $i++) {
                $department->addDecision($this->getReference('decision_' . $i));
            }
            $manager->persist($department);
            $this->addReference('department_' . $departments, $department);
        }

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     * @return array<string>
     */

    public function getDependencies()
    {
        return [
            DecisionFixtures::class,
            DecisionFixtures::class,
        ];
    }
}
