<?php

namespace App\Command;

use App\Entity\LinkDayReport;
use App\Entity\LinkDayReportRow;
use App\Repository\LinkDayReportRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LinkRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronDayReport extends Command
{
// the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:day-report';

    private LinkRepository $linkRepository;
    private EntityManagerInterface $entityManager;
    private LinkDayReportRepository $reportRepository;

    public function __construct(
        LinkRepository $linkRepository,
        LinkDayReportRepository $reportRepository,
        EntityManagerInterface $entityManager)
    {
        $this->linkRepository = $linkRepository;
        $this->reportRepository = $reportRepository;
        $this->entityManager = $entityManager;
        parent::__construct();
    }


    protected function configure()
    {
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $topData = $this->linkRepository->getTopData(100);

        $date = new \DateTime('now');
        if ($this->reportRepository->exists($date)) {
            $this->reportRepository->drop($date);
        }

        $report = new LinkDayReport();
        $report->setDate($date);

        $this->entityManager->beginTransaction();
        $this->entityManager->persist($report);
        $this->entityManager->flush();

        foreach ($topData as $topDataItem) {
            $link = $this->linkRepository->find($topDataItem['id']);
            $reportRow = new LinkDayReportRow();
            $reportRow->setLinkId($link);
            $reportRow->setReportId($report);
            $reportRow->setPosition($topDataItem['cnt']);

            $this->entityManager->persist($reportRow);
            $this->entityManager->flush();
        }

        $this->entityManager->commit();
        return 1;
    }
}
