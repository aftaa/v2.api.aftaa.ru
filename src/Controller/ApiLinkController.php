<?php


namespace App\Controller;


use App\Entity\Block;
use App\Entity\Link;
use App\Service\CorsPolicy;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiLinkController extends AbstractController
{
    /**
     * @param Request $request
     * @Route("link/add")
     * @return JsonResponse
     */
    public function linkAdd(Request $request): JsonResponse
    {
        (new CorsPolicy(['https://aftaa.ru']))->sendHeaders();

        $entityManager = $this->getDoctrine()->getManager();
        $link = new Link;
        $block = $entityManager->getRepository(Block::class)->find($request->get('block_id'));

        $link->setBlock($block)
            ->setName($request->get('name'))
            ->setHref($request->get('href'))
            ->setIcon($request->get('icon'))
            ->setPrivate($request->get('private'));

        $entityManager->flush();

        return new JsonResponse(['id' => $link->getId()]);
    }

    /**
     * @param Request $request
     * @Route("link/save")
     * @return JsonResponse
     */
    public function linkSave(Request $request): JsonResponse
    {
        (new CorsPolicy(['https://aftaa.ru']))->sendHeaders();

        $entityManager = $this->getDoctrine()->getManager();
        $link = $entityManager->getRepository(Link::class)->find($request->get('id'));
        $block = $entityManager->getRepository(Block::class)->find($request->get('block_id'));

        if (!$link) {
            throw $this->createNotFoundException();
        }

        $link->setBlock($block)
            ->setName($request->get('name'))
            ->setHref($request->get('href'))
            ->setIcon($request->get('icon'))
            ->setPrivate($request->get('private'));

        $entityManager->flush();

        return new JsonResponse('');
    }

    /**
     * @Route("link/{id}")
     * @param int $id
     * @return JsonResponse
     */
    public function linkLoad(int $id): JsonResponse
    {
        (new CorsPolicy(['https://aftaa.ru']))->sendHeaders();

        /** @var Link $link */
        $link = $this->getDoctrine()->getRepository(Link::class)->find($id);

        if (!$link) {
            throw $this->createNotFoundException();
        }

        return $this->json([
            'block_id' => $link->getBlock()->getId(),
            'name'     => $link->getName(),
            'href'     => $link->getHref(),
            'icon'     => $link->getIcon(),
            'private'  => $link->getPrivate(),
        ]);
    }
}
