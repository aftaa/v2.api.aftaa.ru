<?php

namespace App\Repository;

use App\Entity\Block;
use App\Entity\Link;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Block|null find($id, $lockMode = null, $lockVersion = null)
 * @method Block|null findOneBy(array $criteria, array $orderBy = null)
 * @method Block[]    findAll()
 * @method Block[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Block::class);
    }

    // /**
    //  * @return Block[] Returns an array of Block objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Block
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return array
     */
    public function getIndexData(): array
    {
        $qb = $this->createQueryBuilder('b')
            ->select('l.id, l.name AS link_name, b.name AS block_name, b.col_num, l.href, l.icon')
            ->join(Link::class, 'l')
            ->where('b.deleted = FALSE AND l.deleted = FALSE AND l.private = FALSE AND b.private = FALSE')
            ->andWhere('l.block=b.id')
            ->orderBy('b.sort')
            ->addOrderBy('l.name');

        $query = $qb->getQuery();
        $rows = $query->execute();

        $data = [];
        foreach ($rows as $row) {
            $data[$row['col_num']][$row['block_name']][] = $row;
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getExpertData(): array
    {
        $result = $this->createQueryBuilder('b')
            ->select('l.id, l.name AS link_name, b.name AS block_name, b.col_num, l.href, l.icon')
            ->innerJoin(Link::class, 'l')
            ->andWhere('l.block = b.id ')
            ->andWhere('b.deleted = FALSE')
            ->andWhere('l.deleted = FALSE')
            ->orderBy('b.sort')
            ->addOrderBy('l.name')
            ->getQuery()
            ->getResult();

        $data = [];
        foreach ($result as $row) {
            $data[$row['col_num']][$row['block_name']][] = $row;
        }

        return $data;
    }
}
