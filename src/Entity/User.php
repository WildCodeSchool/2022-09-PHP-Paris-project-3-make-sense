<?php

namespace App\Entity;

use DateTimeImmutable;
use App\Entity\Opinion;
use App\Entity\Decision;
use App\Entity\Expertise;
use App\Entity\Notification;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[UniqueEntity('email')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\EntityListeners(['App\EntityListener\UserListener'])]
#[Vich\Uploadable]

/** @SuppressWarnings(PHPMD.TooManyPublicMethods)
 *   @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 *   @SuppressWarnings(PHPMD.ExcessivePublicCount)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $poster = null;

    #[Vich\UploadableField(mapping: 'poster_file', fileNameProperty: 'poster')]
    #[Assert\File(
        maxSize: '1M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp']
    )]
    private ?File $posterFile = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Length(min: 1, max: 180)]
    #[Assert\Email]

    private ?string $email = null;

    #[ORM\Column(type: "json")]
    #[Assert\NotNull]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank]
    private ?string $password = null;

    #[ORM\Column(length: 80)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 80)]
    private ?string $firstname = null;

    #[ORM\Column(length: 80)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 80)]
    private ?string $lastname = null;

    #[ORM\Column]
    #[Assert\Positive]
    private ?int $phone = null;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?DateTimeImmutable $createdAt = null;
    private ?string $plainPassword = null;

    #[ORM\Column]
    // #[Assert\NotNull]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Expertise::class)]
    private Collection $expertises;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Decision::class)]
    private Collection $decisions;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Opinion::class)]
    private Collection $opinions;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Notification::class)]
    private Collection $notifications;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Validation::class)]
    private Collection $validations;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable('now');
        $this->validations = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->expertises = new ArrayCollection();
        $this->decisions = new ArrayCollection();
        $this->opinions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setPosterFile(?File $file): self
    {
        $this->posterFile = $file;
        if ($file) {
            $this->updatedAt = new DateTimeImmutable('now');
        }
        return $this;
    }

    public function getPosterFile(): ?File
    {
        return $this->posterFile;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): void
    {
        $this->poster = $poster;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get the value of plainPassword
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * Set the value of plainPassword
     */
    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return Collection<int, Decision>
     */
    public function getExpertise(): Collection
    {
        return $this->expertises;
    }
    public function addExpertise(Expertise $expertise): self
    {
        if (!$this->expertises->contains($expertise)) {
            $this->expertises->add($expertise);
            $expertise->setUser($this);
        }

        return $this;
    }

    public function removeExpertise(Expertise $expertise): self
    {
        if ($this->expertises->removeElement($expertise)) {
            // set the owning side to null (unless already changed)
            if ($expertise->getUser() === $this) {
                $expertise->setUser(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->email;
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
            $decision->setOwner($this);
        }

        return $this;
    }

    public function removeDecision(Decision $decision): self
    {
        if ($this->decisions->removeElement($decision)) {
            // set the owning side to null (unless already changed)
            if ($decision->getOwner() === $this) {
                $decision->setOwner(null);
            }
        }

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
            $opinion->setUser($this);
        }

        return $this;
    }

    public function removeOpinion(Opinion $opinion): self
    {
        if ($this->opinions->removeElement($opinion)) {
            // set the owning side to null (unless already changed)
            if ($opinion->getUser() === $this) {
                $opinion->setUser(null);
            }
        }

        return $this;
    }
    public function getValidations(): Collection
    {
        return $this->validations;
    }

    public function addValidation(Validation $validation): self
    {
        if (!$this->validations->contains($validation)) {
            $this->validations->add($validation);
            $validation->setUser($this);
        }

        return $this;
    }

    public function removeValidation(Validation $validation): self
    {
        if ($this->validations->removeElement($validation)) {
            // set the owning side to null (unless already changed)
            if ($validation->getUser() === $this) {
                $validation->setUser(null);
            }
        }

        return $this;
    }
    public function getNotification(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }

    public function getNotifications(): Collection
    {
        return $this->notifications;
    }
}
