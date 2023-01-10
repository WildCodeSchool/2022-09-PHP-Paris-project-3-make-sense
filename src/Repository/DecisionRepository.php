<?php

namespace App\Repository;

use App\Entity\Decision;
use App\Entity\History;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Decision>
 *
 * @method Decision|null find($id, $lockMode = null, $lockVersion = null)
 * @method Decision|null findOneBy(array $criteria, array $orderBy = null)
 * @method Decision[]    findAll()
 * @method Decision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DecisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Decision::class);
    }

    public function save(Decision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Decision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function findByHistory(?array $histories, ?int $ownerId = null): array
    {
        $decisions = [];

        foreach ($histories as $history) {
            $queryBuilder = $this->createQueryBuilder('d');
            $queryBuilder = $queryBuilder->where('d.id = :decision_id');
            $queryBuilder = $queryBuilder->setParameter('decision_id', $history['decision_id']);

            if ($ownerId != null) {
                $queryBuilder = $queryBuilder->andWhere('d.owner = :user_id');
                $queryBuilder = $queryBuilder->setParameter('user_id', $ownerId);
            }

            $queryBuilder = $queryBuilder->getQuery();

            if (!empty($queryBuilder->getResult())) {
                $decisions[] = $queryBuilder->getResult()[0];
            }
        }

        return $decisions;
    }
}
