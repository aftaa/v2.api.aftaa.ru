<?php

namespace App\Repository;

use App\Entity\Block;
use App\Entity\Link;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Link|null find($id, $lockMode = null, $lockVersion = null)
 * @method Link|null findOneBy(array $criteria, array $orderBy = null)
 * @method Link[]    findAll()
 * @method Link[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Link::class);
    }

    /**
     * @param int $limit
     * @return array
     * @throws Exception
     */
    public function getTopData(int $limit = 17): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select l.id, count(l.id) as cnt, l.name, l.href, l.icon, l.name AS link_name
        from link_view lv join link l on l.id=link_id 
        where l.private = false
        group by l.id order by cnt desc, name limit $limit";

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();

        $data = [];
        foreach ($result->fetchAllAssociative() as $row) {
	        //$row['icon'] = str_replace('https://', 'http://', $row['icon']);
            $data[] = $row;
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

    /**
     * @param int $id
     * @return array
     */
    public function load(int $id): array
    {
        $link = $this->find($id);
        if (!$link) {
            return false;
        }
        /** @var Link $link */
        return [
            'block_id' => $link->getBlock()->getId(),
            'name'     => $link->getName(),
            'href'     => $link->getHref(),
            'icon'     => $link->getIcon(),
            'private'  => $link->getPrivate(),
        ];
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function save(Request $request): bool
    {
        $link = $this->find($request->get('id'));
        $entityManager = $this->getEntityManager();
        $block = $entityManager->getRepository(Block::class)->find($request->get('block_id'));

        if (!$link || !$block) {
            return false;
        }

        $link->setBlock($block)
            ->setName($request->get('name'))
            ->setHref($request->get('href'))
            ->setIcon($request->get('icon'))
            ->setPrivate($request->get('private'));

        $entityManager->flush();
        return true;
    }

    /**
     * @param int $id
     * @return void
     */
    public function remove(int $id): void
    {
        $link = $this->find($id);
        $link->setDeleted(true);
        $this->getEntityManager()->flush();
    }

    /**
     * @param int $id
     * @return void
     */
    public function restore(int $id): void
    {
        $link = $this->find($id);
        $link->setDeleted(false);
        $this->getEntityManager()->flush();
    }
}
