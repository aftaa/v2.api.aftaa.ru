<?php

namespace App\Repository;

use App\Entity\Link;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\FetchMode;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Link|null find($id, $lockMode = null, $lockVersion = null)
 * @method Link|null findOneBy(array $criteria, array $orderBy = null)
 * @method Link[]    findAll()
 * @method Link[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Link::class);
    }

    /**
     * @param int $limit
     * @return array
     * @throws DBALException
     */
    public function getTopData(int $limit = 17): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select count(l.id) as cnt, l.name, l.href, l.icon, l.name AS link_name
        from link_view lv join link l on l.id=link_id 
        where l.private = false
        group by l.id order by cnt desc, name limit $limit";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $data = [];
        foreach ($stmt->fetchAll(FetchMode::STANDARD_OBJECT) as $obj) {
            $data[] = $obj;
        }

        return $data;
    }

    // /**
    //  * @return Link[] Returns an array of Link objects
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
    public function findOneBySomeField($value): ?Link
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
