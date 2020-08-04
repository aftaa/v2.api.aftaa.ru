<?php
namespace App\Controller;

use App\Entity\Block;
use App\Entity\Link;
use App\Repository\BlockRepository;
use App\Repository\LinkRepository;
use App\Service\CorsPolicy;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiController
 * @package App\Controller
 */
class ApiController extends AbstractController
{
    /**
     * @return JsonResponse
     * @Route (
     *     "data/index",
     *     format="json",
     * )
     */
    public function dataIndex(): JsonResponse
    {
        (new CorsPolicy(['https://aftaa.ru']))->sendHeaders();

        /** @var BlockRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Block::class);
        $data = (object)['data' => $repository->getIndexData()];
        return $this->json($data);
    }

    /**
     * @return JsonResponse
     * @Route (
     *     "data/top",
     *     format="json",
     * )
     * @throws DBALException
     */
    public function dataTop(): JsonResponse
    {
        (new CorsPolicy(['https://aftaa.ru']))->sendHeaders();

        /** @var LinkRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Link::class);
        $data = (object)['data' => $repository->getTopData()];
        return $this->json($data);
    }

    /**
     * @return JsonResponse
     * @Route (
     *     "data/expert",
     *     format="json",
     * )
     */
    public function dataExpert(): JsonResponse
    {
        (new CorsPolicy(['https://aftaa.ru']))->sendHeaders();

        /** @var BlockRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Block::class);
        $data = (object)['data' => $repository->getExpertData()];
        return $this->json($data);
    }


}
