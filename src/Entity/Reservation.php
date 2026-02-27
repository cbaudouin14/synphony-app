<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $startDate = null;

    #[ORM\Column]
    private ?\DateTime $expectedEndDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $effectiveEndDate = null;

    #[ORM\Column]
    private ?bool $active = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getExpectedEndDate(): ?\DateTime
    {
        return $this->expectedEndDate;
    }

    public function setExpectedEndDate(\DateTime $expectedEndDate): static
    {
        $this->expectedEndDate = $expectedEndDate;

        return $this;
    }

    public function getEffectiveEndDate(): ?\DateTime
    {
        return $this->effectiveEndDate;
    }

    public function setEffectiveEndDate(?\DateTime $effectiveEndDate): static
    {
        $this->effectiveEndDate = $effectiveEndDate;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }
}
