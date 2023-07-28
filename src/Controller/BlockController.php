<?php


namespace App\Controller;

use App\Entity\Block;
use App\Repository\BlockRepository;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class BlockController extends AbstractController
{
    /**
     * @param BlockRepository $blockRepository
     * @return JsonResponse
     */
    #[Route('/blocks')]
    public function blocksList(BlockRepository $blockRepository): JsonResponse
    {
        $blocks = $blockRepository->getSelectList();
        return $this->json($blocks);
    }

    /**
     * @param Block $block
     * @param BlockRepository $blockRepository
     * @return JsonResponse
     */
    #[Route('/block/remove/{id}')]
    public function blockRemove(Block $block, BlockRepository $blockRepository): JsonResponse
    {
        $blockRepository->remove($block);
        return $this->json(true);
    }

    /**
     * @param Block $block
     * @param BlockRepository $blockRepository
     * @return JsonResponse
     */
    #[Route('/block/restore/{id}')]
    public function blockRestore(Block $block, BlockRepository $blockRepository): JsonResponse
    {
        $blockRepository->restore($block);
        return $this->json(true);
    }

    /**
     * @param stdClass $payload
     * @param BlockRepository $blockRepository
     * @return JsonResponse
     */
    #[Route('/block/add', methods: ['POST'])]
    public function blockAdd(#[MapRequestPayload] stdClass $payload, BlockRepository $blockRepository): JsonResponse
    {
        $block = new BLock();
        $block
            ->setName($payload->name)
            ->setColNum($payload->col_num)
            ->setPrivate($payload->private)
            ->setDeleted(false)
            ->setSort($payload->sort);
        $blockRepository->save($block, true);
        return $this->json($block->getId());
    }

    /**
     * @param stdClass $payload
     * @param BlockRepository $blockRepository
     * @return JsonResponse
     */
    #[Route('/block/save', methods: ['POST'])]
    public function blockSave(#[MapRequestPayload] stdClass $payload, BlockRepository $blockRepository): JsonResponse
    {
        $block = $blockRepository->find($payload->id);
        $block
            ->setName($payload->name)
            ->setColNum($payload->col_num)
            ->setPrivate($payload->private)
            ->setSort($payload->sort);
        $blockRepository->save($block, true);
        return $this->json($block);
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
