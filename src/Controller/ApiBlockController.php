<?php


namespace App\Controller;


use App\Entity\Block;
use App\Entity\Link;
use App\Service\CorsPolicy;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiBlockController extends AbstractController
{
    /**
     * @Route("/blocks")
     * @return JsonResponse
     */
    public function blocksList(): JsonResponse
    {
        (new CorsPolicy(['https://aftaa.ru']))->sendHeaders();
        $blocks = $this->getDoctrine()->getRepository(Block::class)->getSelectList();
        return $this->json($blocks);
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("block/remove/{id}")
     */
    public function blockRemove(int $id): JsonResponse
    {
        (new CorsPolicy(['https://aftaa.ru']))->sendHeaders();
        $this->getDoctrine()->getRepository(Block::class)->remove($id);
        return $this->json(true);
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("block/restore/{id}")
     */
    public function blockRestore(int $id): JsonResponse
    {
        (new CorsPolicy(['https://aftaa.ru']))->sendHeaders();
        $this->getDoctrine()->getRepository(Block::class)->restore($id);
        return $this->json(true);
    }

    /**
     * @param int $id
     * @Route("block/{id}")
     * @return JsonResponse
     */
    public function blockLoad(int $id): JsonResponse
    {
        (new CorsPolicy(['https://aftaa.ru']))->sendHeaders();

        $block = $this->getDoctrine()->getRepository(Block::class)->load($id);
        if (!$block) throw $this->createNotFoundException();
        return $this->json($block);

    }
}