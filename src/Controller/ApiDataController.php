<?php

namespace App\Controller;

use App\Entity\Block;
use App\Entity\Link;
use App\Entity\LinkDayReport;
use App\Entity\LinkDayReportRow;
use App\Repository\BlockRepository;
use App\Repository\LinkDayReportRepository;
use App\Repository\LinkRepository;
use App\Service\CorsPolicy;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class ApiController
 * @package App\Controller
 */
class ApiDataController extends AbstractController
{
    /**
     * @return JsonResponse
     * @Route (
     *     "data/index",
     *     format="json",
     * )
     */
    public function dataIndex(ManagerRegistry $doctrine): JsonResponse
    {
        /** @var BlockRepository $repository */
        $repository = $doctrine->getRepository(Block::class);
        $data = (object)['data' => $repository->getIndexData()];
        return $this->json($data);
    }

    /**
     * @return JsonResponse
     * @Route (
     *     "data/expert/top",
     *     format="json",
     * )
     * @throws DBALException
     */
    public function dataExpertTop(ManagerRegistry $doctrine): JsonResponse
    {
        /** @var LinkRepository $repository */
        $repository = $doctrine->getRepository(Link::class);
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
    public function dataExpert(ManagerRegistry $doctrine): JsonResponse
    {
        /** @var BlockRepository $repository */
        $repository = $doctrine->getRepository(Block::class);
        $data = (object)['data' => $repository->getExpertData()];
        return $this->json($data);
    }

    /**
     * @return JsonResponse
     * @Route (
     *     "data/admin",
     *     format="json",
     * )
     */
    public function dataAdmin(ManagerRegistry $doctrine): JsonResponse
    {
        /** @var BlockRepository $repository */
        $repository = $doctrine->getRepository(Block::class);
        $data = (object)['data' => $repository->getAdminData()];
        return $this->json($data);
    }

    /**
     * @return JsonResponse
     * @Route (
     *     "data/admin/trash",
     *     format="json",
     * )
     */
    public function dataAdminTrash(ManagerRegistry $doctrine): JsonResponse
    {
        /** @var BlockRepository $repository */
        $repository = $doctrine->getRepository(Block::class);
        $data = (object)['data' => $repository->getAdminData(true)];
        return $this->json($data);
    }

    /**
     * @return JsonResponse
     * @Route ("report/list", format="json")
     */
    public function topReportsList(ManagerRegistry $doctrine): JsonResponse
    {
        $repository = $doctrine->getRepository(LinkDayReport::class);
        $reports = $repository->findAll();

        /** @var LinkDayReport $report */
        foreach ($reports as $report) {
            $data[] = ['date' => $report->getDate()->format('d.m.Y')];
        }

        return $this->json($data);
    }

    /**
     * @param \Datetime $date
     * @param LinkDayReportRepository $reportRepository
     * @return JsonResponse
     * @Route("report/links/{date}")
     */
    public function topReportLinks(\Datetime $date, LinkDayReportRepository $reportRepository): JsonResponse
    {
        if ($reportRepository->exists($date)) {
            /** @var LinkDayReport $report */
            $report = $reportRepository->findBy(['date' => $date]);

            /** @var LinkDayReportRow[] $reportLinks */
            $reportLinks = $report[0]->getReportRows();

            foreach ($reportLinks as $reportLink) {
                $data[$reportLink->getLinkId()->getId()] = [
                    'name'     => $reportLink->getLinkId()->getName(),
                    'icon'     => $reportLink->getLinkId()->getIcon(),
                    'position' => $reportLink->getPosition(),
                ];
            }

            usort($data, function ($link1, $link2) {
                return $link1['position'] < $link2['position'];
            });

            $data = array_chunk($data, 10);
        }

        return $this->json($data);
    }
}
