<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Decision;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OpinionRepository;


#[ORM\Entity(repositoryClass: OpinionRepository::class)]
class Opinion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $isLike = null;

    #[ORM\ManyToOne(inversedBy: 'opinions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Decision $decision = null;

    #[ORM\ManyToOne(inversedBy: 'opinions')]
    private ?User $user = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    public function __construct()
    {
        $this->message = "";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsLike(): ?bool
    {
        return $this->isLike;
    }

    public function setIsLike(bool $isLike): self
    {
        $this->isLike = $isLike;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }
}
