<?php

namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notification>
 *
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function save(Notification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Notification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findNotification(?int $userId = null): array
    {
        $queryBuilder = $this->createQueryBuilder('n');
        $queryBuilder
            ->select('d.id', 'IDENTITY(d.owner) as owner', 'd.status', 'd.title', 'sum(exp.isExpert) as isExpert')
            ->join('\App\Entity\Decision', 'd', 'WITH', 'd.id = n.decision')
            ->join('d.departments', 'dep_dec')
            ->leftjoin('App\Entity\Expertise', 'exp', 'WITH', 'exp.department = dep_dec and exp.user = :user_id')
            ->groupBy('d.id')
            ->where('n.user = :user_id')
            ->setParameter('user_id', $userId);

        $queryBuilder = $queryBuilder->getQuery();

        return $queryBuilder->getResult();
    }

    public function getTotalByUser(int $userId): int
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.user)')
            ->where('n.user = :userid')
            ->setParameter('userid', $userId)
            ->getQuery()
            ->getResult()[0][1];
    }
}
