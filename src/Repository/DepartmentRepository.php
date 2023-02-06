<?php

namespace App\Repository;

use App\Entity\Department;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Department>
 *
 * @method Department|null find($id, $lockMode = null, $lockVersion = null)
 * @method Department|null findOneBy(array $criteria, array $orderBy = null)
 * @method Department[]    findAll()
 * @method Department[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepartmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Department::class);
    }

    public function save(Department $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Department $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findAllExpertiseByDepartement(?int $userId = null): mixed
    {
        $queryBuilder = $this->createQueryBuilder('d')
            ->select('e.isExpert', 'd.name as dep_name', 'e.id as exp_id', 'd.id as dep_id')
            ->leftjoin('App\Entity\Expertise', 'e', 'WITH', 'e.department = d.id and e.user = :user_id')
            ->setParameter('user_id', $userId);
        $queryBuilder = $queryBuilder->getQuery();
        return $queryBuilder->getResult();
    }
}
