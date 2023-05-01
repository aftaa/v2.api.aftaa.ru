<?php


namespace App\Controller;


use App\Entity\Block;
use App\Entity\Link;
use App\Entity\View;
use App\Service\Cors;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class ApiLinkController extends AbstractController
{
    public function __construct(
        private readonly Cors $cors,
    )
    {
    }

    /**
     * @Route("link/save")
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return JsonResponse
     */
    public function linkSave(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $link = $entityManager->getRepository(Link::class)->save($request);

        if (!$link) {
            throw $this->createNotFoundException();
        }

        return $this->json('', 200, $this->cors->getHeaders());
    }

    /**
     * @Route("link/add")
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return JsonResponse
     */
    public function linkAdd(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $block = $entityManager->getRepository(Block::class)->find($request->get('block_id'));

        $link = new Link;
        $link->setBlock($block)
            ->setName($request->get('name'))
            ->setHref($request->get('href'))
            ->setIcon($request->get('icon'))
            ->setDeleted(false)
            ->setPrivate($request->get('private'));

        $entityManager->persist($link);
        $entityManager->flush();

        return $this->json(['id' => $link->getId()], 200, $this->cors->getHeaders());
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("link/remove/{id}")
     */
    public function linkRemove(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $doctrine->getRepository(Link::class)->remove($id);
        return $this->json(true, 200, $this->cors->getHeaders());
    }

    /**
     * @Route("link/restore/{id}")
     * @param int $id
     * @param ManagerRegistry $doctrine
     * @return JsonResponse
     */
    public function linkRestore(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $doctrine->getRepository(Link::class)->restore($id);
        return $this->json(true, 200, $this->cors->getHeaders());
    }

    /**
     * @Route("link/{id}")
     * @param int $id
     * @param ManagerRegistry $doctrine
     * @return JsonResponse
     */
    public function linkLoad(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $link = $doctrine->getRepository(Link::class)->load($id);
        if (!$link) throw $this->createNotFoundException();
        return $this->json($link, 200, $this->cors->getHeaders());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getFavicon(Request $request): string
    {
        $originUrl = $request->get('origin');
        $name = $request->get('name');

        $faviconContent = file_get_contents($originUrl);

        if (false === $faviconContent) {
            throw new Exception("Can't get $originUrl");
        }

        $faviconExt = pathinfo($originUrl, PATHINFO_EXTENSION);
        $faviconFileName = "$name.$faviconExt";
        $faviconFileName = "$_SERVER[DOCUMENT_ROOT]/favicons/$faviconFileName";

        if (!file_put_contents($faviconFileName, $faviconContent)) {
            throw new Exception("Can't set $faviconFileName");
        }

        $myFaviconUrl = "/favicons/$name.$faviconExt";

        return $this->json($myFaviconUrl, 200, $this->cors->getHeaders());
    }

    /**
     * @Route("view/{id}")
     * @param int $id
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return JsonResponse
     */
    public function linkView(int $id, Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $link = $entityManager->getRepository(Link::class)->find($id);
        $view = new View;
        $view->setDateTime(new \DateTime('now'));
        $view->setIp4(ip2long($request->server->get('REMOTE_ADDR')));
        $view->setIsGuest(false);
        $view->setLink($link);

        $entityManager->persist($view);
        $entityManager->flush();
        return $this->json(['id' => $view->getId()], 200, $this->cors->getHeaders());
    }
}
