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

    public function findLastUpdatedByStatus(string $status, int $maxresult = 0): mixed
    {
        // $conn = $this->entityManager->getConnection();

        $queryBuilder = $this->createQueryBuilder('d')
                             ->where('d.status = :status')
                             ->orderBy('d.createdAt')
                             ->setParameter('status', $status);


        // $sqlmax = 'SELECT MAX(updated_at) AS max, decision_id  FROM history GROUP BY decision_id';
        // $sql = 'SELECT h.decision_id, h.status, h.updated_at from history h INNER JOIN (' . $sqlmax . ') ';
        // $sql .= 'ms ON ms.decision_id = h.decision_id and max = h.updated_at and h.status = :status ';
        // $sql .= 'ORDER BY h.updated_at DESC';
        // if ($maxresult) {
        //     $sql .= ' LIMIT ' . $maxresult;
        // }

        if ($maxresult) {
            $queryBuilder = $queryBuilder->setMaxResults($maxresult); 
        }

        $queryBuilder = $queryBuilder->getQuery();

        // $stmt = $conn->prepare($sql);
        // $resultSet = $stmt->executeQuery(['status' => $status]);

        // return ($resultSet->fetchAllAssociative());
        return $queryBuilder->getResult();

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
