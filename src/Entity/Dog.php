<?php

namespace App\Entity;

use App\Repository\DogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DogRepository::class)]
class Dog
{
    const GENDERS = ['male' => 'dog.gender.male', 'female' => 'dog.gender.female'];
    const TITLES = ['champion' => 'CHCZ', 'grandchampion' => 'GrChCZ', 'international_champion' => 'IC'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 10)]
    private ?string $gender = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $bornAt = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $diedAt = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $chipNumber = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $registrationNumber = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $titles = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoFilename = null;

    #[ORM\Column]
    private bool $isStud = false;

    #[ORM\Column]
    private bool $isActive = true;

    #[ORM\ManyToOne(inversedBy: 'dogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Kennel $kennel = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Breed $breed = null;

    /** @var Collection<int, Litter> */
    #[ORM\OneToMany(targetEntity: Litter::class, mappedBy: 'mother')]
    private Collection $littersAsMother;

    /** @var Collection<int, Litter> */
    #[ORM\OneToMany(targetEntity: Litter::class, mappedBy: 'father')]
    private Collection $littersAsFather;

    public function __construct()
    {
        $this->littersAsMother = new ArrayCollection();
        $this->littersAsFather = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getGender(): ?string { return $this->gender; }
    public function setGender(string $gender): static { $this->gender = $gender; return $this; }
    public function getBornAt(): ?\DateTimeInterface { return $this->bornAt; }
    public function setBornAt(?\DateTimeInterface $d): static { $this->bornAt = $d; return $this; }
    public function getDiedAt(): ?\DateTimeInterface { return $this->diedAt; }
    public function setDiedAt(?\DateTimeInterface $d): static { $this->diedAt = $d; return $this; }
    public function getChipNumber(): ?string { return $this->chipNumber; }
    public function setChipNumber(?string $v): static { $this->chipNumber = $v; return $this; }
    public function getRegistrationNumber(): ?string { return $this->registrationNumber; }
    public function setRegistrationNumber(?string $v): static { $this->registrationNumber = $v; return $this; }
    public function getTitles(): ?string { return $this->titles; }
    public function setTitles(?string $v): static { $this->titles = $v; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $d): static { $this->description = $d; return $this; }
    public function getPhotoFilename(): ?string { return $this->photoFilename; }
    public function setPhotoFilename(?string $f): static { $this->photoFilename = $f; return $this; }
    public function isStud(): bool { return $this->isStud; }
    public function setIsStud(bool $v): static { $this->isStud = $v; return $this; }
    public function isActive(): bool { return $this->isActive; }
    public function setIsActive(bool $v): static { $this->isActive = $v; return $this; }
    public function getKennel(): ?Kennel { return $this->kennel; }
    public function setKennel(?Kennel $k): static { $this->kennel = $k; return $this; }
    public function getBreed(): ?Breed { return $this->breed; }
    public function setBreed(?Breed $b): static { $this->breed = $b; return $this; }
    public function getAge(): ?int
    {
        if (!$this->bornAt) return null;
        $end = $this->diedAt ?? new \DateTime();
        return (int) $this->bornAt->diff($end)->y;
    }
    public function isMale(): bool { return $this->gender === 'male'; }
    public function getLittersAsMother(): Collection { return $this->littersAsMother; }
    public function getLittersAsFather(): Collection { return $this->littersAsFather; }
    public function __toString(): string { return $this->name ?? ''; }
}
