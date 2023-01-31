<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Decision;
use App\Entity\Department;
use Faker;
use DateTimeImmutable;

class DecisionFixtures extends Fixture implements DependentFixtureInterface
{
    public const NB_DECISION = 26;

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($j = 0; $j < self::NB_DECISION; $j++) {
            $decision = new Decision();
            $decision->setTitle('Decision' . $j);
            $decision->setDescription($faker->text(25));
            $decision->setImpacts($faker->text(25));
            $decision->setBenefits($faker->text(25));
            $decision->setRisks($faker->text(25));
            $keys = array_keys(DECISION::STATUSES);
            $decision->setStatus($keys[$j % 6]);
            $decision->setLikeThreshold($faker->numberBetween(30, 70));

            $decision->setCreatedAt(new DateTimeImmutable('now'));
            $decision->setEndAt(new DateTimeImmutable('03/23/2023'));

            $decision->setOwner($this->getReference('user_' . ($j % UserFixtures::NB_USER)));

            $this->addReference('decision_' . $j, $decision);

            $keys = array_keys(Department::DEPARTMENTS);
            $decision->addDepartment($this->getReference('department_' . $keys[rand(0, 7)]));

            $decision->addDepartment($this->getReference('department_' . $keys[rand(0, 7)]));

            $decision->addDepartment($this->getReference('department_' . $keys[rand(0, 7)]));

            $manager->persist($decision);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            DepartmentFixtures::class
        ];
    }
}
