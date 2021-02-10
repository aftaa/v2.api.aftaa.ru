<?php

namespace App\Entity;

use App\Repository\ViewRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ViewRepository::class)
 * @ORM\Table("link_view")
 */
class View
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Link::class, inversedBy="views")
     * @ORM\JoinColumn(nullable=false)
     */
    private $link;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_time;

    /**
     * @ORM\Column(type="integer")
     */
    private $ip4;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_guest;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLink(): ?Link
    {
        return $this->link;
    }

    public function setLink(?Link $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->date_time;
    }

    public function setDateTime(\DateTimeInterface $date_time): self
    {
        $this->date_time = $date_time;

        return $this;
    }

    public function getIp4(): ?int
    {
        return $this->ip4;
    }

    public function setIp4(int $ip4): self
    {
        $this->ip4 = $ip4;

        return $this;
    }

    public function getIsGuest(): ?bool
    {
        return $this->is_guest;
    }

    public function setIsGuest(bool $is_guest): self
    {
        $this->is_guest = $is_guest;

        return $this;
    }
}
