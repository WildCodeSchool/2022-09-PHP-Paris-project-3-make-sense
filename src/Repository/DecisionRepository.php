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


    public function search(string $title, string $status): array
    {
        $conn = $this->entityManager->getConnection();

        $sql1 = "SELECT h.decision_id, status, updated_at, dd.department_id, department.name from history h ";
        $sql1 .= "INNER JOIN (SELECT MAX(updated_at) as t1, decision_id AS tt FROM `history` GROUP BY decision_id) ms ";
        $sql1 .= "ON tt = h.decision_id and t1 = h.updated_at AND h.status IN('Brouillon','Aboutie') ";
        $sql1 .= " INNER JOIN decision_department dd ON dd.decision_id = h.decision_id ";
        $sql1 .= " INNER JOIN department ON department.id = dd.department_id";
        $statement = $conn->prepare($sql1);
        // $resultSet = $statement->executeQuery(['title'  => "%" . $title . "%",
        //                                     // 'status' => $status
        // ]);


        $query = $this->entityManager->createQuery(
            'SELECT *
            FROM App\Entity\History h
            INNER JOIN decision d'
        );
        dd($query->getResult());


        return [];
    }

    public function findByDecision(?array $histories, ?int $ownerId = null): array
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

    public function searchTest()
    {
        $entityManager = $this->getEntityManager();


        return $query->getResult();
    }
}


