<?php

namespace App\Entity;

use App\Repository\DecisionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Comments;
use App\Entity\DecisionHistory;
use App\Entity\Skill;

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
    private ?string $impact = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $benefits = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $risks = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\Date]
    private ?\DateTimeInterface $startAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\Date]
    private ?\DateTimeInterface $endAt = null;

    #[ORM\Column]
    private ?int $likeThreshold = null;

    #[ORM\ManyToMany(targetEntity: comments::class, inversedBy: 'decisions')]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'decision', targetEntity: Opinion::class, orphanRemoval: true)]
    private Collection $opinions;

    #[ORM\OneToMany(mappedBy: 'decision', targetEntity: Decisionhistory::class, orphanRemoval: true)]
    private Collection $decisionhistories;

    #[ORM\OneToMany(mappedBy: 'decision', targetEntity: Validation::class)]
    private Collection $validations;

    #[ORM\ManyToMany(targetEntity: skill::class, inversedBy: 'decisions')]
    private Collection $skill;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->opinions = new ArrayCollection();
        $this->decisionhistories = new ArrayCollection();
        $this->validations = new ArrayCollection();
        $this->skill = new ArrayCollection();
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

    public function getImpact(): ?string
    {
        return $this->impact;
    }

    public function setImpact(?string $impact): self
    {
        $this->impact = $impact;

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
     * @return Collection<int, comments>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
        }

        return $this;
    }

    public function removeComment(comments $comment): self
    {
        $this->comments->removeElement($comment);

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
     * @return Collection<int, skill>
     */
    public function getSkill(): Collection
    {
        return $this->skill;
    }

    public function addSkill(skill $skill): self
    {
        if (!$this->skill->contains($skill)) {
            $this->skill->add($skill);
        }

        return $this;
    }

    public function removeSkill(skill $skill): self
    {
        $this->skill->removeElement($skill);

        return $this;
    }
}
