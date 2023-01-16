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

    public function findLastStatus(int $decisionId): array
    {
        $conn = $this->entityManager->getConnection();
        $sql1 = 'SELECT MAX(updated_at) AS max, decision_id as dec_id FROM history h WHERE h.decision_id = :decision_id';
        $sql = 'SELECT * From decision d INNER JOIN history h1 ON h1.decision_id = d.id ';
        $sql .= 'INNER JOIN (' . $sql1 . ') ';
        $sql .= 'ms ON dec_id = h1.decision_id and max = h1.updated_at ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['decision_id' => $decisionId]);
        return ($resultSet->fetchAssociative());
    }

    public function isLike(int $like)
    {
        $like = 'SELECT * decision, COUNT(*) AS ';
    }
}
