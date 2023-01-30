<?php

namespace App\DataFixtures;

use App\Entity\Department as Department;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class DepartmentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach (Department::DEPARTMENTS as $key => $departmentName) {
            $department = new Department();
            $departmentName = "";
            $department->setName($key);
            $this->addReference('department_' . $key, $department);
            $manager->persist($department);
            $manager->flush();
        }
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
        ];
    }
}
