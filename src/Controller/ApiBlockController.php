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
     * @return JsonResponse
     */
    public function blocksList(ManagerRegistry $doctrine): JsonResponse
    {
        (new CorsPolicy(['https://aftaa.ru']))->sendHeaders();
        $blocks = $doctrine->getRepository(Block::class)->getSelectList();
        return $this->json($blocks);
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("block/remove/{id}")
     */
    public function blockRemove(int $id, ManagerRegisrty $doctrine): JsonResponse
    {
        (new CorsPolicy(['https://aftaa.ru']))->sendHeaders();
        $doctrine->getRepository(Block::class)->remove($id);
        return $this->json(true);
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("block/restore/{id}")
     */
    public function blockRestore(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        (new CorsPolicy(['https://aftaa.ru']))->sendHeaders();
        $doctrine->getRepository(Block::class)->restore($id);
        return $this->json(true);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("block/add")
     */
    public function blockAdd(Request $request): JsonResponse
    {
        (new CorsPolicy(['https://aftaa.ru']))->sendHeaders();
        $block = $this->getDoctrine()->getRepository(Block::class)->add($request);
        if (!$block) {
            throw $this->createNotFoundException();
        }
        return $this->json($block->getId());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("block/save")
     */
    public function blockSave(Request $request): JsonResponse
    {
        (new CorsPolicy(['https://aftaa.ru']))->sendHeaders();
        $block = $this->getDoctrine()->getRepository(Block::class)->save($request);
        if (!$block) {
            throw $this->createNotFoundException();
        }
        return $this->json($block);
    }

    /**
     * @param int $id
     * @Route("block/{id}")
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