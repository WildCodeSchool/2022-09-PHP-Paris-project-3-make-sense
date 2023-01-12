<?php

namespace App\Repository;

use App\Entity\History;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<History>
 *
 * @method History|null find($id, $lockMode = null, $lockVersion = null)
 * @method History|null findOneBy(array $criteria, array $orderBy = null)
 * @method History[]    findAll()
 * @method History[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoryRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($registry, History::class);
    }

    public function save(History $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(History $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLastUpdatedByStatus(string $status, int $maxresult = 0): mixed
    {
        $conn = $this->entityManager->getConnection();

        $sqlmax = 'SELECT MAX(updated_at) AS max, decision_id  FROM history GROUP BY decision_id';
        $sql = 'SELECT h.decision_id, h.status, h.updated_at from history h INNER JOIN (' . $sqlmax . ') ';
        $sql .= 'ms ON ms.decision_id = h.decision_id and max = h.updated_at and h.status = :status ';
        $sql .= 'ORDER BY h.updated_at DESC';
        if ($maxresult) {
            $sql .= ' LIMIT ' . $maxresult;
        }

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['status' => $status]);

        return ($resultSet->fetchAllAssociative());
    }
}
