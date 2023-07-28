<?php


namespace App\Controller;

use App\Entity\Block;
use App\Repository\BlockRepository;
use App\Service\CorsPolicy;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class ApiBlockController extends BaseController
{
    /**
     * @param BlockRepository $blockRepository
     * @return JsonResponse
     */
    #[Route('/blocks')]
    public function blocksList(BlockRepository $blockRepository): JsonResponse
    {
        $blocks = $blockRepository->getSelectList();
        return $this->jsonAndHeader($blocks);
    }

    /**
     * @param int $id
     * @param ManagerRegistry $doctrine
     * @return JsonResponse
     */
    #[Route('/block/remove/{id}')]
    public function blockRemove(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $doctrine->getRepository(Block::class)->remove($id);
        return $this->jsonAndHeader(true);
    }

    /**
     * @param int $id
     * @param ManagerRegistry $doctrine
     * @return JsonResponse
     */
    #[Route('/block/restore/{id}')]
    public function blockRestore(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $doctrine->getRepository(Block::class)->restore($id);
        return $this->jsonAndHeader(true);
    }

    /**
     * @param Request $request
     * @param BlockRepository $blockRepository
     * @return JsonResponse
     */
    #[Route('/block/add', methods: ['POST'])]
    public function blockAdd(Request $request, BlockRepository $blockRepository): JsonResponse
    {
        $blockRepository->add($request);
        if (!$block) {
            throw $this->createNotFoundException();
        }
        return $this->jsonAndHeader($block->getId());
    }

    /**
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return JsonResponse
     */
    #[Route('/block/save', methods: ['POST'])]
    public function blockSave(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $block = $doctrine->getRepository(Block::class)->save($request);
        if (!$block) {
            throw $this->createNotFoundException();
        }
        return $this->jsonAndHeader($block);
    }

    /**
     * @param Block $block
     * @return JsonResponse
     */
    #[Route('/block/{id}')]
    public function load(Block $block): JsonResponse
    {
        return $this->json($block);
    }
}
