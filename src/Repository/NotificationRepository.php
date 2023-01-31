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

    /* #SQL
    SELECT d.id, d.status, d.title, d.owner_id, sum(exp.is_expert) from notification as n
    INNER JOIN decision as d on n.decision_id = d.id
    INNER JOIN decision_department as dd on n.decision_id = dd.decision_id
    LEFT JOIN expertise as exp ON exp.department_id = dd.department_id and exp.user_id = 54
    WHERE n.user_id=54 and user_read=0
    GROUP BY d.id
    ORDER BY d.created_at;
    */

    public function findNotification(?int $userId = null): array
    {
        $queryBuilder = $this->createQueryBuilder('n');
        $queryBuilder
            ->select(
                'd',
                'sum(exp.isExpert) as isExpert'
            )
            ->join('\App\Entity\Decision', 'd', 'WITH', 'd.id = n.decision')
            ->join('d.departments', 'dep_dec')
            ->leftjoin('App\Entity\Expertise', 'exp', 'WITH', 'exp.department = dep_dec and exp.user = :user_id')
            ->groupBy('d.id')
            ->where('n.user = :user_id and n.userRead = 0')
            ->orderby('d.createdAt', 'DESC')
            ->setParameter('user_id', $userId);

        $queryBuilder = $queryBuilder->getQuery();

        return $queryBuilder->getResult();
    }

    public function sumByUser(int $userId): int
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.user)')
            ->where('n.user = :userid and n.userRead = 0')
            ->setParameter('userid', $userId)
            ->getQuery()
            ->getOneOrNullResult()['1'];
    }
}
