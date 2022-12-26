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

    public function findByStatus2(string $status, int $userId): void
    {
        // $em = $this->getEntityManager();
        // $query = $em->createQuery(
        //     "SELECT '*' FROM App\Entity\Decision as d INNER JOIN (
        //         SELECT h.decision_id, status, updated_at from App\Entity\History h
        //             INNER JOIN (SELECT MAX(updated_at) as t1, decision_id FROM App\Entity\History
        //                  GROUP BY decision_id) ms ON ms.decision_id = h.decision_id
        //                     and t1 = h.updated_at and h.status = :status) mt ON
        //                     mt.decision_id = d.id and d.owner_id= :userId;"
        // );

        // $query->setParameters(array(
        //     'status' => $status,
        //     'userId' => $userId
        // ));


        // $query = $em->createQuery("
        //         SELECT IDENTITY(h1.decision), MAX(h1.updatedAt) t1
        //          FROM App\Entity\History h1 GROUP BY h1.decision
        //          ");

        // $query = $em->createQuery("
        //     SELECT IDENTITY(h2.decision_id), h2.status, h2.updatedAt from App\Entity\History h2 JOIN
        //     (SELECT IDENTITY(h1.decision), MAX(h1.updatedAt) t1
        //         FROM App\Entity\History h1 GROUP BY h1.decision) ms
        //     ON ms.decision = h2.decision and t1 = h2.updatedAt and h2.status = 'Brouillon'");

        // dd($query->getResult());

        // return $query->getResult();
    }

    //    /**
    //     * @return Decision[] Returns an array of Decision objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Decision
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
