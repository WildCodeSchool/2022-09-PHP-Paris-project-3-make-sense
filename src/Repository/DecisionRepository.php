<?php

namespace App\Repository;

use App\Entity\Decision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

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
    private EntityManagerInterface $entityManager;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Decision::class);
        $this->entityManager = $entityManager;
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

    public function findLikeTitle(string $title): array
    {
        $queryBuilder = $this->createQueryBuilder('d')
            ->where('d.title LIKE :title')
            ->setParameter('title', '%' . $title . '%')
            ->orderBy('d.title', 'ASC')
            ->getQuery();
        return $queryBuilder->getResult();
    }

    public function findLikeDomainName(): array
    {
        $conn = $this->entityManager->getConnection();

        // $sql1 = 'SELECT MAX(updated_at) AS max, decision_id  FROM history GROUP BY decision_id';
        // $sql  = 'SELECT h.decision_id, h.status, h.updated_at FROM history h INNER JOIN (' . $sql1 . ') ';
        // $sql .= 'ms ON ms.decision_id = h.decision_id and max = h.updated_at ';
        // $sql .= 'INNER JOIN decision_department AS dd ON dd.decision_id = ms.decision_id ';
        // $sql .= 'INNER JOIN department AS dp ON dd.department_id = dp.id and dp.name IN ("Commercial","Informatique","Ressources Humaines","Comptabilité","Marketing","Finance","Achats","Juridique") ';
        // $sql .= 'ORDER BY h.updated_at DESC ';
        
        $sql = 'SELECT * FROM decision AS d INNER JOIN (SELECT h.decision_id, status, updated_at from history h INNER JOIN (SELECT MAX(updated_at) as t1, decision_id FROM `history` GROUP BY decision_id) ms ON ms.decision_id = h.decision_id and t1 = h.updated_at) mt ON mt.decision_id = d.id INNER JOIN decision_department AS dd ON dd.decision_id = d.id INNER JOIN department AS dp ON dd.department_id = dp.id and dp.name IN("Commercial","Informatique") ORDER BY h.updated_at DESC';

        $statement = $conn->prepare($sql);
        $resultSet = $statement->executeQuery();

        return ($resultSet->fetchAllAssociative());
    }
}
