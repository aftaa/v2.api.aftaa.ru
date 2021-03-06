<?php

namespace App\Repository;

use App\Entity\Block;
use App\Entity\Link;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * @return array
     */
    public function getSelectList()
    {
        $rows = $this->createQueryBuilder('b')
            ->where('b.deleted = false')
            ->orderBy('b.name')
            ->getQuery()
            ->getResult();

        $blocks = [];
        foreach ($rows as $row) {
            $blocks[$row->getId()] = $row->getName();
        }

        return $blocks;
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

    /**
     * @param int $id
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(int $id)
    {
        $block = $this->find($id);
        $block->setDeleted(true);
        $this->getEntityManager()->flush();
    }

    /**
     * @param int $id
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function restore(int $id)
    {
        $block = $this->find($id);
        $block->setDeleted(false);
        $this->getEntityManager()->flush();
    }

    /**
     * @param int $id
     * @return array|false
     */
    public function load(int $id): array
    {
        $block = $this->find($id);
        if (!$block) {
            return false;
        }
        /** @var block $block */
        $block = [
            'name'    => $block->getName(),
            'sort'    => $block->getSort(),
            'col_num' => $block->getColNum(),
            'private' => $block->getPrivate(),
        ];
        return $block;
    }

    /**
     * @param Request $request
     * @return false
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Request $request)
    {
        $block = $this->find($request->get('id'));
        $entityManager = $this->getEntityManager();

        if (!$block) {
            return false;
        }

        $block
            ->setName($request->get('name'))
            ->setColNum($request->get('col_num'))
            ->setPrivate($request->get('private'))
            ->setSort($request->get('sort'));

        $entityManager->flush();
        return $block;
    }

    /**
     * @param Request $request
     * @return Block
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Request $request): Block
    {
        $block = new BLock;
        $block
            ->setName($request->get('name'))
            ->setColNum($request->get('col_num'))
            ->setPrivate($request->get('private'))
            ->setDeleted(false)
            ->setSort($request->get('sort'));
        $this->getEntityManager()->persist($block);
        $this->getEntityManager()->flush();

        return $block;
    }

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

    /**
     * @param bool $getTrash
     * @return array
     */
    public function getAdminData(bool $getTrash = false): array
    {
        $qb = $this->createQueryBuilder('b')
            ->select('l.id AS link_id, b.id AS block_id, l.name AS link_name, b.name AS block_name')
            ->addSelect('b.col_num, l.href, l.icon, b.private AS block_private, l.private AS link_private')
            ->join(Link::class, 'l')
            ->where('b.id = l.block')
            ->andWhere('l.deleted = FALSE AND b.deleted = FALSE')
            ->orderBy('b.sort')
            ->addOrderBy('l.name');

        if ($getTrash) {
            $qb->addSelect('b.deleted AS block_deleted, l.deleted AS link_deleted')
                ->where('b.deleted = TRUE OR l.deleted = TRUE ')
                ->andWhere('l.block = b.id');
        }

        $result = $qb->getQuery()->getResult();

        $data = [];
        foreach ($result as $row) {
            $data[$row['col_num']][$row['block_name']]['links'][] = $row;
            $data[$row['col_num']][$row['block_name']]['block_id'] = $row['block_id'];
            if ($getTrash) {
                $data[$row['col_num']][$row['block_name']]['block_deleted'] = $row['block_deleted'];
            }
            $data[$row['col_num']][$row['block_name']]['block_private'] = $row['block_private'];
        }

        return $data;
    }
}
