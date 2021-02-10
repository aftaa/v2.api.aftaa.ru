<?php

namespace App\Repository;

use App\Entity\LinkDayReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LinkDayReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method LinkDayReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method LinkDayReport[]    findAll()
 * @method LinkDayReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkDayReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LinkDayReport::class);
    }

    /**
     * @param \Datetime $date
     * @return bool
     */
    public function exists(\Datetime $date): bool
    {
        return (bool)$this->createQueryBuilder('r')
            ->andWhere('r.date = :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param \Datetime $date
     * @return int|mixed|string
     */
    public function drop(\Datetime $date)
    {
        return $this->createQueryBuilder()
            ->delete()
            ->where('date = :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return LinkDayReport[] Returns an array of LinkDayReport objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LinkDayReport
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
