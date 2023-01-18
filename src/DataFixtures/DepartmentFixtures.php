<?php

namespace App\DataFixtures;

use App\Entity\Department as department;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class DepartmentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
         $faker = Faker\Factory::create('fr_FR');
        foreach (Department::DEPARTMENTS as $department) {
             $dept = new Department();
             $dept->setName($department);
             $dept->addDecision($this->getReference('decision_' . $faker->numberBetween(1, 8)));
             $manager->persist($dept);
             $this->addReference('department_' . $department, $dept);
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
        ];
    }
}
