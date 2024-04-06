<?php

namespace App\Entity;

use App\Repository\NotesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotesRepository::class)]
class Notes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 55)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $Content = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $Datetime;

    #[ORM\Column(nullable: true)]
    private ?bool $Urgency = null;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    private ?Admin $Author = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'notes')]
    private Collection $Owner;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->Content;
    }

    public function setContent(string $Content): static
    {
        $this->Content = $Content;

        return $this;
    }

    public function __construct()
    {
        $this->Datetime = new \DateTimeImmutable(); // Initialise $Datetime lors de la création de l'objet
        $this->Owner = new ArrayCollection();
    }

    // ... les autres getters et setters ...

    public function getDatetime(): \DateTimeImmutable
    {
        return $this->Datetime;
    }

    public function setDatetime(?\DateTimeImmutable $Datetime): static
    {
        $this->Datetime = $Datetime;

        return $this;
    }

    public function isUrgency(): ?bool
    {
        return $this->Urgency;
    }

    public function setUrgency(?bool $Urgency): static
    {
        $this->Urgency = $Urgency;

        return $this;
    }

    public function getAuthor(): ?Admin
    {
        return $this->Author;
    }

    public function setAuthor(?Admin $Author): static
    {
        $this->Author = $Author;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getOwner(): Collection
    {
        return $this->Owner;
    }

    public function addOwner(User $owner): static
    {
        if (!$this->Owner->contains($owner)) {
            $this->Owner->add($owner);
        }

        return $this;
    }

    public function removeOwner(User $owner): static
    {
        $this->Owner->removeElement($owner);

        return $this;
    }
}
