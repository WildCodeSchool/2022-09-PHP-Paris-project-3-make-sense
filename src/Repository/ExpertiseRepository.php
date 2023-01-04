<?php

namespace App\Repository;

use App\Entity\Expertise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Expertise>
 *
 * @method Expertise|null find($id, $lockMode = null, $lockVersion = null)
 * @method Expertise|null findOneBy(array $criteria, array $orderBy = null)
 * @method Expertise[]    findAll()
 * @method Expertise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpertiseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Expertise::class);
    }

    public function save(Expertise $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Expertise $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function countExpertiseByDecision(?int $userId = null): array
    {
        $queryBuilder = $this->createQueryBuilder('e')
            ->select('d.id as decisionid', 'SUM(e.isExpert) as sum')
            ->join('\App\Entity\Decision', 'd')
            ->join('d.departments', 'dep', 'WITH', 'e.department = dep and e.user = :user_id and e.isExpert=1')
            ->groupBy('d.id')
            ->setParameter('user_id', $userId);

        $queryBuilder = $queryBuilder->getQuery();

        return $queryBuilder->getResult();
    }
}
