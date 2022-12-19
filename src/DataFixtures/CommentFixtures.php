<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Comment;
use DateTimeInterface;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public const NB_USER = 5;
    public const NB_DECISION = 5;
    public const NB_COMMENT = 5;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($userId = 0; $userId < self::NB_USER; $userId++) {
            for ($decisionId = 0; $decisionId < self::NB_DECISION; $decisionId++) {
                for ($commentId = 0; $commentId < self::NB_COMMENT; $commentId++) {
                    $comment = new Comment();
                    $comment->setContent($faker->text(55));
                    $comment->setUser($this->getReference('user_' . $userId));
                    $comment->setDecision($this->getReference('decision_' .
                        (string)(($userId * self::NB_DECISION) + $decisionId)));
                    $comment->setCreatedAt(new \DateTimeImmutable('now'));
                    $manager->persist($comment);
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
