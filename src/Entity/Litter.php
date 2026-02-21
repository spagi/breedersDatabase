<?php

namespace App\Entity;

use App\Repository\LitterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LitterRepository::class)]
class Litter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $litterLetter = null; // A, B, C... designation

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $bornAt = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $availableFrom = null;

    #[ORM\Column]
    private int $totalPuppies = 0;

    #[ORM\Column]
    private int $malePuppies = 0;

    #[ORM\Column]
    private int $femalePuppies = 0;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private bool $isPlanned = false;

    #[ORM\Column]
    private bool $hasPuppiesAvailable = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coverPhotoFilename = null;

    #[ORM\ManyToOne(inversedBy: 'litters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Kennel $kennel = null;

    #[ORM\ManyToOne(inversedBy: 'littersAsMother')]
    private ?Dog $mother = null;

    #[ORM\ManyToOne(inversedBy: 'littersAsFather')]
    private ?Dog $father = null;

    /** @var Collection<int, Puppy> */
    #[ORM\OneToMany(targetEntity: Puppy::class, mappedBy: 'litter', cascade: ['persist', 'remove'])]
    private Collection $puppies;

    public function __construct()
    {
        $this->puppies = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getLitterLetter(): ?string { return $this->litterLetter; }
    public function setLitterLetter(?string $v): static { $this->litterLetter = $v; return $this; }
    public function getBornAt(): ?\DateTimeInterface { return $this->bornAt; }
    public function setBornAt(?\DateTimeInterface $d): static { $this->bornAt = $d; return $this; }
    public function getAvailableFrom(): ?\DateTimeInterface { return $this->availableFrom; }
    public function setAvailableFrom(?\DateTimeInterface $d): static { $this->availableFrom = $d; return $this; }
    public function getTotalPuppies(): int { return $this->totalPuppies; }
    public function setTotalPuppies(int $v): static { $this->totalPuppies = $v; return $this; }
    public function getMalePuppies(): int { return $this->malePuppies; }
    public function setMalePuppies(int $v): static { $this->malePuppies = $v; return $this; }
    public function getFemalePuppies(): int { return $this->femalePuppies; }
    public function setFemalePuppies(int $v): static { $this->femalePuppies = $v; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $d): static { $this->description = $d; return $this; }
    public function isPlanned(): bool { return $this->isPlanned; }
    public function setIsPlanned(bool $v): static { $this->isPlanned = $v; return $this; }
    public function hasPuppiesAvailable(): bool { return $this->hasPuppiesAvailable; }
    public function setHasPuppiesAvailable(bool $v): static { $this->hasPuppiesAvailable = $v; return $this; }
    public function getCoverPhotoFilename(): ?string { return $this->coverPhotoFilename; }
    public function setCoverPhotoFilename(?string $f): static { $this->coverPhotoFilename = $f; return $this; }
    public function getKennel(): ?Kennel { return $this->kennel; }
    public function setKennel(?Kennel $k): static { $this->kennel = $k; return $this; }
    public function getMother(): ?Dog { return $this->mother; }
    public function setMother(?Dog $d): static { $this->mother = $d; return $this; }
    public function getFather(): ?Dog { return $this->father; }
    public function setFather(?Dog $d): static { $this->father = $d; return $this; }

    /** @return Collection<int, Puppy> */
    public function getPuppies(): Collection { return $this->puppies; }
    public function addPuppy(Puppy $puppy): static { if (!$this->puppies->contains($puppy)) { $this->puppies->add($puppy); $puppy->setLitter($this); } return $this; }
    public function removePuppy(Puppy $puppy): static { if ($this->puppies->removeElement($puppy)) { if ($puppy->getLitter() === $this) { $puppy->setLitter(null); } } return $this; }

    public function getTitle(): string
    {
        $parts = [];
        if ($this->litterLetter) $parts[] = 'Vrh ' . $this->litterLetter;
        if ($this->bornAt) $parts[] = $this->bornAt->format('d.m.Y');
        return implode(' - ', $parts) ?: 'Vrh #' . $this->id;
    }
}
