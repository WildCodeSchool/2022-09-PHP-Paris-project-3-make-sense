<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Content;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ContentFixtures extends Fixture implements DependentFixtureInterface
{
    public const NB_USER = 5;
    public const NB_DECISION = 5;
    public const NB_CONTENT = 5;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($user_id = 0; $user_id < self::NB_USER; $user_id++) {
            for ($decision_id = 0; $decision_id < self::NB_DECISION; $decision_id++) {
                for ($content_id = 0; $content_id < self::NB_CONTENT; $content_id++) {
                    $content = new Content();
                    $content->setComment($faker->text(55));
                    $content->setUser($this->getReference('user_' . $user_id));
                    $content->setDecision($this->getReference('decision_' . ($user_id*self::NB_DECISION)+$decision_id));
                    $content->setCreatedAt(new \DateTime('now'));
                    $this->addReference('content_' . ((($user_id*self::NB_DECISION)+$decision_id)*self::NB_CONTENT)+$content_id, $content);
                    $manager->persist($content);
                }
            }    
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            DecisionFixtures::class,
        ];
    }
}
