<?php

namespace App\Entity;

use App\Repository\LinkDayReportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LinkDayReportRepository::class)
 */
class LinkDayReport
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity=LinkDayReportRow::class, mappedBy="report_id", orphanRemoval=true)
     */
    private $reportRows;

    public function __construct()
    {
        $this->reportRows = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection|LinkDayReportRow[]
     */
    public function getReportRows(): Collection
    {
        return $this->reportRows;
    }

    public function addReportRow(LinkDayReportRow $reportRow): self
    {
        if (!$this->reportRows->contains($reportRow)) {
            $this->reportRows[] = $reportRow;
            $reportRow->setReportId($this);
        }

        return $this;
    }

    public function removeReportRow(LinkDayReportRow $reportRow): self
    {
        if ($this->reportRows->contains($reportRow)) {
            $this->reportRows->removeElement($reportRow);
            // set the owning side to null (unless already changed)
            if ($reportRow->getReportId() === $this) {
                $reportRow->setReportId(null);
            }
        }

        return $this;
    }
}
