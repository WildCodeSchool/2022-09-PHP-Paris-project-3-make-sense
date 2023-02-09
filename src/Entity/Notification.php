<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use App\Entity\Decision;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    public const STATUS_SHOW = "show";

    public const NOTIFICATIONS_MESSAGE =
    [
        Decision::STATUS_DRAFT  => "Erreur",
        Decision::STATUS_CURRENT  => "Veuillez donner votre avis sur cette décision",
        Decision::STATUS_FIRST_DECISION => "Cette décision est en attente du compte-rendu",
        Decision::STATUS_CONFLICT => "Cette décision est en attente de la décision des experts",
        Decision::STATUS_DONE => "La décision a été prise",
        Decision::STATUS_UNDONE => "La décision a été prise",
    ];

    public const NOTIFICATIONS_BUTTON =
    [
        Decision::STATUS_DRAFT  => "Erreur",
        Decision::STATUS_CURRENT => "Donner son avis",
        Decision::STATUS_FIRST_DECISION => "Compte-rendu",
        Decision::STATUS_CONFLICT => "Avis de l'expert",
        Decision::STATUS_DONE => "",
        Decision::STATUS_UNDONE => "",
        self::STATUS_SHOW => ""
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    private ?Decision $decision = null;

    #[ORM\Column]
    private bool $userRead;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDecision(): ?Decision
    {
        return $this->decision;
    }

    public function setDecision(?Decision $decision): self
    {
        $this->decision = $decision;

        return $this;
    }

    public function getUserRead(): bool
    {
        return $this->userRead;
    }

    public function isUserRead(): bool
    {
        return $this->userRead;
    }

    public function setUserRead(bool $userRead): self
    {
        $this->userRead = $userRead;

        return $this;
    }
}
