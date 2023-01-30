<?php

namespace App\Repository;

use App\Entity\Decision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Department;
use App\Entity\History;
use PDO;

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

    public function search(?string $title, ?string $status, ?array $departements = null): array
    {
        $querybuilder = $this->createQueryBuilder('d')
            ->select('d', 'dp')
            ->join('d.departments', 'dp');
        if ($title !== null) {
            $querybuilder->where('d.title LIKE :title')
            ->setParameter('title', '%' . $title . '%');
        }
        if ($departements !== null) {
            $querybuilder = $querybuilder->orWhere('dp.name IN (:name)')
            ->setParameter('name', $departements);
        }
        if ($status !== Decision::STATUS_ALL) {
            $querybuilder = $querybuilder->orWhere('d.status = :status')
            ->setParameter('status', $status);
        }
        $querybuilder = $querybuilder->orderBy('d.title', 'ASC')
            ->getQuery();
        return $querybuilder->getResult();
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
    SELECT d.id, d.like_threshold, d.status, sum(o.is_like), count(o.is_like)  FROM `decision` as d
    INNER JOIN opinion as o ON o.decision_id = d.id  and d.id=251
    */

    public function findFirstDecisionLike(int $decisionId): mixed
    {
        $queryBuilder = $this->createQueryBuilder('d')
            ->select('d.status', 'd.likeThreshold', 'sum(o.isLike) as sumLike', 'count(o.isLike) as countLike')
            ->join('App\Entity\Opinion', 'o', 'WITH', 'o.decision = d.id and d.id = :decisionId')
            ->setParameter(':decisionId', $decisionId);

        $queryBuilder = $queryBuilder->getQuery();

        return $queryBuilder->getOneOrNullResult();
    }

    // #SQL
    // SELECT d FROM `decision` as d
    // WHERE d.end_at <= NOW() and d.status="current";

    public function findCurrentEndBefore(): mixed
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

    public function findConflict(): mixed
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
