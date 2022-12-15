<?php

namespace App\Entity;

use App\Repository\DecisionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Content;
use App\Entity\DecisionHistory;
use \Datetime;

#[ORM\Entity(repositoryClass: DecisionRepository::class)]
class Decision
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 1, max: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $impacts = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $benefits = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $risks = null;

    #[ORM\Column]
    private ?int $likeThreshold = null;

    #[ORM\OneToMany(mappedBy: 'decision', targetEntity: Opinion::class, orphanRemoval: true)]
    private Collection $opinions;

    #[ORM\OneToMany(mappedBy: 'decision', targetEntity: Decisionhistory::class, orphanRemoval: true)]
    private Collection $decisionhistories;

    #[ORM\OneToMany(mappedBy: 'decision', targetEntity: Validation::class)]
    private Collection $validations;

    #[ORM\OneToMany(mappedBy: 'decision', targetEntity: Department::class)]
    private Collection $departments;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\Type("\DateTimeInterface")]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\Type("\DateTimeInterface")]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'decision', targetEntity: Notification::class)]
    private Collection $notifications;

    #[ORM\ManyToOne(inversedBy: 'decisions')]
    private ?User $createdBy = null;

    #[ORM\OneToMany(mappedBy: 'decision', targetEntity: Content::class)]
    private Collection $contents;

    public function __construct()
    {
        // $this->contents = new ArrayCollection();
        $this->opinions = new ArrayCollection();
        $this->decisionhistories = new ArrayCollection();
        $this->validations = new ArrayCollection();
        $this->departments = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->createdAt =  new \DateTime('now');
        $this->updatedAt =  new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImpacts(): ?string
    {
        return $this->impacts;
    }

    public function setImpacts(?string $impacts): self
    {
        $this->impacts = $impacts;

        return $this;
    }

    public function getBenefits(): ?string
    {
        return $this->benefits;
    }

    public function setBenefits(string $benefits): self
    {
        $this->benefits = $benefits;

        return $this;
    }

    public function getRisks(): ?string
    {
        return $this->risks;
    }

    public function setRisks(?string $risks): self
    {
        $this->risks = $risks;

        return $this;
    }

    public function getLikeThreshold(): ?int
    {
        return $this->likeThreshold;
    }

    public function setLikeThreshold(int $likeThreshold): self
    {
        $this->likeThreshold = $likeThreshold;

        return $this;
    }

    /**
     * @return Collection<int, contents>
     */
    public function getContents(): Collection
    {
        return $this->contents;
    }

    public function addContent(content $content): self
    {
        if (!$this->contents->contains($content)) {
            $this->contents->add($content);
        }

        return $this;
    }

    public function removeContent(content $content): self
    {
        $this->contents->removeElement($content);

        return $this;
    }

    /**
     * @return Collection<int, Opinion>
     */
    public function getOpinions(): Collection
    {
        return $this->opinions;
    }

    public function addOpinion(Opinion $opinion): self
    {
        if (!$this->opinions->contains($opinion)) {
            $this->opinions->add($opinion);
            $opinion->setDecision($this);
        }

        return $this;
    }

    public function removeOpinion(Opinion $opinion): self
    {
        if ($this->opinions->removeElement($opinion)) {
            // set the owning side to null (unless already changed)
            if ($opinion->getDecision() === $this) {
                $opinion->setDecision(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Decisionhistory>
     */
    public function getDecisionhistories(): Collection
    {
        return $this->decisionhistories;
    }

    public function addDecisionhistory(Decisionhistory $decisionhistory): self
    {
        if (!$this->decisionhistories->contains($decisionhistory)) {
            $this->decisionhistories->add($decisionhistory);
            $decisionhistory->setDecision($this);
        }

        return $this;
    }

    public function removeDecisionhistory(Decisionhistory $decisionhistory): self
    {
        if ($this->decisionhistories->removeElement($decisionhistory)) {
            // set the owning side to null (unless already changed)
            if ($decisionhistory->getDecision() === $this) {
                $decisionhistory->setDecision(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Validation>
     */
    public function getValidations(): Collection
    {
        return $this->validations;
    }

    public function addValidation(Validation $validation): self
    {
        if (!$this->validations->contains($validation)) {
            $this->validations->add($validation);
            $validation->setDecision($this);
        }

        return $this;
    }

    public function removeValidation(Validation $validation): self
    {
        if ($this->validations->removeElement($validation)) {
            // set the owning side to null (unless already changed)
            if ($validation->getDecision() === $this) {
                $validation->setDecision(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Department>
     */
    public function getDepartments(): Collection
    {
        return $this->departments;
    }

    public function addDepartment(Department $department): self
    {
        if (!$this->departments->contains($department)) {
            $this->departments->add($department);
            $department->setDecision($this);
        }

        return $this;
    }

    public function removeDepartment(Department $department): self
    {
        if ($this->departments->removeElement($department)) {
            // set the owning side to null (unless already changed)
            if ($department->getDecision() === $this) {
                $department->setDecision(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setDecision($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getDecision() === $this) {
                $notification->setDecision(null);
            }
        }

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }
}
