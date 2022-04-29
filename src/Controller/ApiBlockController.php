<?php


namespace App\Controller;


use App\Entity\Block;
use App\Service\CorsPolicy;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class ApiBlockController extends AbstractController
{
    /**
     * @Route("/blocks")
     * @param ManagerRegistry $doctrine
     * @return JsonResponse
     */
    public function blocksList(ManagerRegistry $doctrine): JsonResponse
    {
        (new CorsPolicy(['https://aftaa.ru']))->sendHeaders();
        $blocks = $doctrine->getRepository(Block::class)->getSelectList();
        return $this->json($blocks);
    }

    /**
     * @Route("block/remove/{id}")
     * @param int $id
     * @param ManagerRegistry $doctrine
     * @return JsonResponse
     */
    public function blockRemove(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        (new CorsPolicy(['https://aftaa.ru']))->sendHeaders();
        $doctrine->getRepository(Block::class)->remove($id);
        return $this->json(true);
    }

    /**
     * @Route("block/restore/{id}")
     * @param int $id
     * @param ManagerRegistry $doctrine
     * @return JsonResponse
     */
    public function blockRestore(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        (new CorsPolicy(['https://aftaa.ru']))->sendHeaders();
        $doctrine->getRepository(Block::class)->restore($id);
        return $this->json(true);
    }

    /**
     * @Route("block/add")
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return JsonResponse
     */
    public function blockAdd(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        (new CorsPolicy(['https://aftaa.ru']))->sendHeaders();
        $block = $doctrine->getRepository(Block::class)->add($request);
        if (!$block) {
            throw $this->createNotFoundException();
        }
        return $this->json($block->getId());
    }

    /**
     * @Route("block/save")
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return JsonResponse
     */
    public function blockSave(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        (new CorsPolicy(['https://aftaa.ru']))->sendHeaders();
        $block = $doctrine->getRepository(Block::class)->save($request);
        if (!$block) {
            throw $this->createNotFoundException();
        }
        return $this->json($block);
    }

    /**
     * @Route("block/{id}")
     * @param int $id
     * @param ManagerRegistry $doctrine
     * @return JsonResponse
     */
    public function blockLoad(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        (new CorsPolicy(['https://aftaa.ru']))->sendHeaders();

        $block = $doctrine->getRepository(Block::class)->load($id);
        if (!$block) throw $this->createNotFoundException();
        return $this->json($block);

    }
}