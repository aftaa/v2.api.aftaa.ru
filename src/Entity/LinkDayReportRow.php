<?php

namespace App\Entity;

use App\Repository\LinkDayReportRowRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LinkDayReportRowRepository::class)
 */
class LinkDayReportRow
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=LinkDayReport::class, inversedBy="reportRows")
     * @ORM\JoinColumn(nullable=false)
     */
    private $report_id;

    /**
     * @ORM\ManyToOne(targetEntity=Link::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $link_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReportId(): ?LinkDayReport
    {
        return $this->report_id;
    }

    public function setReportId(?LinkDayReport $report_id): self
    {
        $this->report_id = $report_id;

        return $this;
    }

    public function getLinkId(): ?Link
    {
        return $this->link_id;
    }

    public function setLinkId(?Link $link_id): self
    {
        $this->link_id = $link_id;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }
}
