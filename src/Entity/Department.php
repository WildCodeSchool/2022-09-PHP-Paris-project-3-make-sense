<?php

namespace App\Entity;

use App\Entity\Decision;
use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DepartmentRepository::class)]
class Department
{
    public const DEPARTMENT_HUMAN_RESSOURCES = 'human_ressources';
    public const DEPARTMENT_SALES = 'sales';
    public const DEPARTMENT_ACCOUNTING = 'accounting';
    public const DEPARTMENT_COMPUTER_SCIENCE = 'computer_science';
    public const DEPARTMENT_MARKETING = 'marketing';
    public const DEPARTMENT_FINANCE = 'finance';
    public const DEPARTMENT_BUYER = 'buyer';
    public const DEPARTMENT_LEGAL = 'legal';

    public const DEPARTMENTS = [
        self::DEPARTMENT_HUMAN_RESSOURCES =>  'Ressources Humaines',
        self::DEPARTMENT_SALES => 'Commercial',
        self::DEPARTMENT_ACCOUNTING => 'Comptabilité',
        self::DEPARTMENT_COMPUTER_SCIENCE => 'Informatique',
        self::DEPARTMENT_MARKETING => 'Marketing',
        self::DEPARTMENT_FINANCE => 'Finance',
        self::DEPARTMENT_BUYER => 'Achats',
        self::DEPARTMENT_LEGAL => 'Juridique',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(min: 1, max: 50)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'department', targetEntity: Expertise::class)]
    private Collection $expertises;

    #[ORM\ManyToMany(targetEntity: Decision::class, mappedBy: 'departments')]
    private Collection $decisions;
    public function __construct()
    {
        $this->expertises = new ArrayCollection();
        $this->decisions = new ArrayCollection();
       
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    /**
     * @return Collection<int, Expertise>
     */
    public function getExpertises(): Collection
    {
        return $this->expertises;
    }
    public function addExpertise(Expertise $expertise): self
    {
        if (!$this->expertises->contains($expertise)) {
            $this->expertises->add($expertise);
            $expertise->setDepartment($this);
        }

        return $this;
    }
    public function removeExpertise(Expertise $expertise): self
    {
        if ($this->expertises->removeElement($expertise)) {
            // set the owning side to null (unless already changed)
            if ($expertise->getDepartment() === $this) {
                $expertise->setDepartment(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return Collection<int, Decision>
     */
    public function getDecisions(): Collection
    {
        return $this->decisions;
    }
    public function addDecision(Decision $decision): self
    {
        if (!$this->decisions->contains($decision)) {
            $this->decisions->add($decision);
            $decision->addDepartment($this);
        }

        return $this;
    }
    public function removeDecision(Decision $decision): self
    {
        if ($this->decisions->removeElement($decision)) {
            $decision->removeDepartment($this);
        }
        return $this;
    }  
}