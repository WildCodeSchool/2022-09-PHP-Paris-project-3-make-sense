<?php

namespace App\DataFixtures;

use App\Entity\Department;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
class DepartmentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 1; $i < 7; $i++) {
            $department = new Department();
            $department->setName($faker->words(3, true));
            $department->AddDecision($this->getReference('decision_' . $faker->numberBetween(1, 5)));
            $manager->persist($department);
            $this->addReference('department_' . $i, $department);
        }

        $manager->flush();
    }
	/**
	 * This method must return an array of fixtures classes
	 * on which the implementing class depends on
	 * @return array<string>
	 */
	public function getDependencies() {
        return [
            DecisionFixtures::class,
         ];
	}
}
