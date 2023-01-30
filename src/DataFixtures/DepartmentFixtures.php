<?php

namespace App\DataFixtures;

use App\Entity\Department as Department;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DepartmentFixtures extends Fixture 
{
    public function load(ObjectManager $manager): void
    {
        foreach (Department::DEPARTMENTS as $key => $departmentName) {
            $department = new Department();
            $departmentName = "";
            $department->setName($key);
            // $department->addDecision($this->getReference('decision_' . $faker->numberBetween(1, 8)));
            $manager->persist($department);
            $this->addReference('department_' . $key, $department);
        }

        $manager->flush();
    }
}
