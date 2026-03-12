<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DateTimeInterface;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Le champ ne peut pas être vide')]
    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $startDate;

    /* ENDDATE = PAS UTILE MAIS JE N'ARRIVE PAS A LE RETIRER SANS TOUT CASSER */
    /**
     * @var string A "Y-m-d H:i:s" formatted value
     */

    #[Assert\NotBlank(message: 'Le champ ne peut pas être vide')]
    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $endDate;

    /**
     * @var string A "Y-m-d H:i:s" formatted value
     */

    #[Assert\NotBlank(message: 'Le champ ne peut pas être vide')]
    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $expectedEndDate;

    /**
     * @var string A "Y-m-d H:i:s" formatted value
     */

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $effectiveEndDate;

    #[ORM\Column]
    private ?bool $active = null;

    /**
     * @var Collection<int, Book>
     */
    #[ORM\ManyToMany(targetEntity: Book::class, inversedBy: 'reservations')]
    #[Assert\Count(min: 1, minMessage: 'Sélectionner au moins un livre')]
    private Collection $book;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->book = new ArrayCollection();
        $this->startDate = new \DateTime();
        $this->endDate = new \DateTime('+30 days');
        $this->expectedEndDate = new \DateTime('+30 days');
        $this->effectiveEndDate = new \DateTime('+30 days');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;
        $this->updateActiveStatus();
        return $this;
    }

    public function getEndDate(): DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;
        $this->updateActiveStatus();
        return $this;
    }

    public function getExpectedEndDate(): DateTimeInterface
    {
        return $this->expectedEndDate;
    }

    public function setExpectedEndDate(DateTimeInterface $expectedEndDate): static
    {
        $this->expectedEndDate = $expectedEndDate;
        return $this;
    }

    public function getEffectiveEndDate(): DateTimeInterface
    {
        return $this->effectiveEndDate;
    }

    public function setEffectiveEndDate(DateTimeInterface $effectiveEndDate): static
    {
        $this->effectiveEndDate = $effectiveEndDate;
        return $this;
    }

    public function updateActiveStatus(): static
    {
        $now = new \DateTime();

        if ($this->startDate && $this->endDate) {
            $this->active = ($this->startDate <= $now) && ($this->endDate >= $now);
        } else {
            $this->active = false;
        }

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

    /**
     * @return Collection<int, Book>
     */
    public function getBook(): Collection
    {
        return $this->book;
    }

    public function addBook(Book $book): static
    {
        if (!$this->book->contains($book)) {
            if ($book->isAvailable()) {
                $this->book->add($book);

                $book->getReservations()->add($this);

                $book->setStock($book->getStock() - 1);
            } else {
                throw new \Exception("Le livre '{$book->getTitle()}' n'est pas disponible.");
            }
        }

        return $this;
    }

    public function removeBook(Book $book): static
    {
        if ($this->book->removeElement($book)) {
            $book->removeReservation($this);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    // /**
    //  * @return Collection<int, User>
    //  */
    // public function getUser(): Collection
    // {
    //     return $this->User;
    // }

    // public function addUser(User $user): static
    // {
    //     if (!$this->User->contains($user)) {
    //         $this->User->add($user);
    //         $user->setReservation($this);
    //     }

    //     return $this;
    // }

    // public function removeUser(User $user): static
    // {
    //     if ($this->User->removeElement($user)) {
    //         // set the owning side to null (unless already changed)
    //         if ($user->getReservation() === $this) {
    //             $user->setReservation(null);
    //         }
    //     }

    //     return $this;
    // }
}