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

    public function search(string $title): array
    {
        return $this->createQueryBuilder('d')
                    ->join('d.departments', 'dp')
                    ->select('d', 'dp')
                    ->where('d.title LIKE :title')
                    ->setParameter('title', '%' . $title . '%')
                    ->orderBy('d.title', 'ASC')
                    ->getQuery()
                    ->getResult();
    }
}
