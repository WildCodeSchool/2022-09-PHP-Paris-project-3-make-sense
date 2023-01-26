<?php

namespace App\Repository;

use App\Entity\Decision;
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

    public function findByStatus(string $status, int $maxres = 0, ?int $ownerId = null): mixed
    {

        $queryBuilder = $this->createQueryBuilder('d')
            ->where('d.status = :status')
            ->orderBy('d.createdAt', 'DESC')
            ->setParameter('status', $status);

        if ($maxres) {
            $queryBuilder = $queryBuilder->setMaxResults($maxres);
        }

        if ($ownerId) {
            $queryBuilder = $queryBuilder->andWhere('d.owner = :ownerId');
            $queryBuilder = $queryBuilder->setParameter('ownerId', $ownerId);
        }

        $queryBuilder = $queryBuilder->getQuery();

        return $queryBuilder->getResult();
    }

    /*
    #SQL
    SELECT d FROM `decision` as d
    WHERE d.end_at <= NOW() and d.status="current";
    */

    public function findByStatusEndAt(): mixed
    {
        $queryBuilder = $this->createQueryBuilder('d')
            ->select('d')
            ->where('d.status = :status and d.endAt <= :today')
            ->setParameter(':today', date('Y-m-d h:i:s'))
            ->setParameter(':status', Decision::STATUS_CURRENT);

        $queryBuilder = $queryBuilder->getQuery();

        return $queryBuilder->getResult();
    }

    /*
    SELECT d.id, d.like_threshold, d.status, sum(v.is_approved), count(v.is_approved)  FROM `decision` as d
    INNER JOIN validation as v ON v.decision_id = d.id and d.end_at <= NOW()
    GROUP BY d.id
    HAVING d.status="conflict";
    */

    public function findByConflict(): mixed
    {
        $queryBuilder = $this->createQueryBuilder('d')
            ->select('d', 'sum(v.isApproved) as sumApproved', 'count(v.isApproved) as countApproved')
            ->join('App\Entity\Validation', 'v', 'WITH', 'v.decision = d.id and d.endAt <= :today')
            ->groupBy('d.id')
            ->having('d.status = :status')
            ->setParameter(':today', date('Y-m-d h:i:s'))
            ->setParameter(':status', Decision::STATUS_CONFLICT);

        $queryBuilder = $queryBuilder->getQuery();

        return $queryBuilder->getResult();
    }
}
