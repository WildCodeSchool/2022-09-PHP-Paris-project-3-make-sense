<?php

namespace App\Entity;

use App\Repository\DecisionHistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Decision;

#[ORM\Entity(repositoryClass: DecisionHistoryRepository::class)]
class DecisionHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\Date]
    private ?\DateTimeInterface $startAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\Date]
    private ?\DateTimeInterface $endAt = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(min: 1, max: 50)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'decisionhistories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?decision $decision = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDecision(): ?decision
    {
        return $this->decision;
    }

    public function setDecision(?decision $decision): self
    {
        $this->decision = $decision;

        return $this;
    }
}
