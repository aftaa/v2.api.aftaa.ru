<?php

namespace App\Repository;

use App\Entity\LinkDayReportRow;
use Datetime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LinkDayReportRow|null find($id, $lockMode = null, $lockVersion = null)
 * @method LinkDayReportRow|null findOneBy(array $criteria, array $orderBy = null)
 * @method LinkDayReportRow[]    findAll()
 * @method LinkDayReportRow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkDayReportRowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LinkDayReportRow::class);
    }

    // /**
    //  * @return LinkDayReportRow[] Returns an array of LinkDayReportRow objects
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
    public function findOneBySomeField($value): ?LinkDayReportRow
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
