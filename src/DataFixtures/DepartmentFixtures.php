<?php

namespace App\DataFixtures;

use App\Entity\Department;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;
class DepartmentFixtures extends Fixture implements DependentFixtureInterface
{
    public const DEPARTMENTS=[
        'Ressources Humaines',
        'Commercial',
        'Comptabilité',
        'Informatique',
        'Marketing',
        'Finance',
        'Achats',
        'Juridique',
    ];
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();
        foreach (self::DEPARTMENTS as $departments) {
            $department = new Department();
            $department->setName($departments);
            $department->addDecision($this->getReference('decision_' . $faker->numberBetween(1, 8)));
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
	public function getDependencies() {
        return [
            DecisionFixtures::class,
            DecisionFixtures::class,
         ];
	}
}
