<?php

namespace App\Entity;

use App\Repository\ExpertiseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExpertiseRepository::class)]
class Expertise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'expertises')]
    private ?Department $department = null;

    #[ORM\ManyToOne(inversedBy: 'expertises')]
    private ?User $user = null;

    #[ORM\Column]
    private ?bool $isExpert = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

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

    public function isIsExpert(): ?bool
    {
        return $this->isExpert;
    }

    public function setIsExpert(bool $isExpert): self
    {
        $this->isExpert = $isExpert;

        return $this;
    }
}
