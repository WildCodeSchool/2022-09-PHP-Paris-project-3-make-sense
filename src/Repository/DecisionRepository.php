<?php

namespace App\Repository;

use App\Entity\Decision;
use App\Entity\User;
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

    public function search(string $title, ?string $status = null, ?string $domaines = null): array
    {
        $querybuilder = $this->createQueryBuilder('d')
            ->join('d.departments', 'dp')
            ->select('d', 'dp')
            ->where('d.title LIKE :title');
        if ($domaines !== null) {
            $querybuilder = $querybuilder->andWhere('dp.name LIKE :name');
        }
        if ($status !== null) {
            $querybuilder = $querybuilder->andWhere('d.status LIKE :status');
        }
            $querybuilder = $querybuilder->setParameter('title', '%' . $title . '%');
        if ($status !== null) {
            $querybuilder = $querybuilder->setParameter('status', '%' . $status . '%');
        }
        if ($domaines !== null) {
            $querybuilder = $querybuilder->setParameter('name', '%' . $domaines . '%');
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
}
