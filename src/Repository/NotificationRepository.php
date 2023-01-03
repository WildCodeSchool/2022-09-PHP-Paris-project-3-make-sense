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


    public function findAllNotification(?int $userId = null)
    {
    
        $queryBuilder = $this->createQueryBuilder('n')
            ->select('d.title', 'h.status, d.updatedAt')
            ->join('App\Entity\History', 'h', 'WITH', 'n.History = h.id')
            ->join('App\Entity\Decision', 'd', 'WITH', 'h.decision = d.id and n.user = :user_id')
            ->setParameter('user_id', $userId);

        $queryBuilder = $queryBuilder->getQuery();

        // dd($queryBuilder->getResult());

        return $queryBuilder->getResult();
    }

}
