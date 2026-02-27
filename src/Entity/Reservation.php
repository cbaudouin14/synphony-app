<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $endDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $expectedEndDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
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

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate): static
    {
        $this->endDate = $endDate;

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

    public function setEffectiveEndDate(\DateTime $effectiveEndDate): static
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
