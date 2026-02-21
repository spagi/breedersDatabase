<?php

namespace App\Entity;

use App\Repository\KennelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: KennelRepository::class)]
#[UniqueEntity(fields: ['slug'], message: 'validation.slug_exists')]
class Kennel
{
    // Purpose/usage constants
    const PURPOSES = [
        'show' => 'kennel.purpose.show',
        'work' => 'kennel.purpose.work',
        'sport' => 'kennel.purpose.sport',
        'family' => 'kennel.purpose.family',
        'hunting' => 'kennel.purpose.hunting',
        'herding' => 'kennel.purpose.herding',
        'companion' => 'kennel.purpose.companion',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 220, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $descriptionCz = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $descriptionEn = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $descriptionSk = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $region = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $country = null; // ISO 3166-1 alpha-3: CZE, SVK, etc.

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 180, nullable: true)]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $website = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $facebook = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $instagram = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logoFilename = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coverFilename = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $purposes = [];

    #[ORM\Column]
    private bool $isActive = false;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToOne(inversedBy: 'kennel', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    /** @var Collection<int, Breed> */
    #[ORM\ManyToMany(targetEntity: Breed::class, inversedBy: 'kennels')]
    private Collection $breeds;

    /** @var Collection<int, Dog> */
    #[ORM\OneToMany(targetEntity: Dog::class, mappedBy: 'kennel', cascade: ['persist', 'remove'])]
    private Collection $dogs;

    /** @var Collection<int, Litter> */
    #[ORM\OneToMany(targetEntity: Litter::class, mappedBy: 'kennel', cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(['bornAt' => 'DESC'])]
    private Collection $litters;

    /** @var Collection<int, KennelPhoto> */
    #[ORM\OneToMany(targetEntity: KennelPhoto::class, mappedBy: 'kennel', cascade: ['persist', 'remove'])]
    private Collection $photos;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->breeds = new ArrayCollection();
        $this->dogs = new ArrayCollection();
        $this->litters = new ArrayCollection();
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getSlug(): ?string { return $this->slug; }
    public function setSlug(string $slug): static { $this->slug = $slug; return $this; }

    public function getDescription(string $locale = 'cs'): ?string
    {
        return match($locale) {
            'sk' => $this->descriptionSk ?? $this->descriptionCz,
            'en' => $this->descriptionEn ?? $this->descriptionCz,
            default => $this->descriptionCz,
        };
    }

    public function getDescriptionCz(): ?string { return $this->descriptionCz; }
    public function setDescriptionCz(?string $d): static { $this->descriptionCz = $d; return $this; }
    public function getDescriptionEn(): ?string { return $this->descriptionEn; }
    public function setDescriptionEn(?string $d): static { $this->descriptionEn = $d; return $this; }
    public function getDescriptionSk(): ?string { return $this->descriptionSk; }
    public function setDescriptionSk(?string $d): static { $this->descriptionSk = $d; return $this; }

    public function getCity(): ?string { return $this->city; }
    public function setCity(?string $city): static { $this->city = $city; return $this; }
    public function getRegion(): ?string { return $this->region; }
    public function setRegion(?string $region): static { $this->region = $region; return $this; }
    public function getCountry(): ?string { return $this->country; }
    public function setCountry(?string $country): static { $this->country = $country; return $this; }
    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(?string $phone): static { $this->phone = $phone; return $this; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(?string $email): static { $this->email = $email; return $this; }
    public function getWebsite(): ?string { return $this->website; }
    public function setWebsite(?string $website): static { $this->website = $website; return $this; }
    public function getFacebook(): ?string { return $this->facebook; }
    public function setFacebook(?string $fb): static { $this->facebook = $fb; return $this; }
    public function getInstagram(): ?string { return $this->instagram; }
    public function setInstagram(?string $ig): static { $this->instagram = $ig; return $this; }
    public function getLogoFilename(): ?string { return $this->logoFilename; }
    public function setLogoFilename(?string $f): static { $this->logoFilename = $f; return $this; }
    public function getCoverFilename(): ?string { return $this->coverFilename; }
    public function setCoverFilename(?string $f): static { $this->coverFilename = $f; return $this; }
    public function getPurposes(): ?array { return $this->purposes; }
    public function setPurposes(?array $p): static { $this->purposes = $p; return $this; }
    public function isActive(): bool { return $this->isActive; }
    public function setIsActive(bool $v): static { $this->isActive = $v; return $this; }
    public function isVerified(): bool { return $this->isVerified; }
    public function setIsVerified(bool $v): static { $this->isVerified = $v; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(?\DateTimeImmutable $d): static { $this->updatedAt = $d; return $this; }
    public function getOwner(): ?User { return $this->owner; }
    public function setOwner(?User $owner): static { $this->owner = $owner; return $this; }

    /** @return Collection<int, Breed> */
    public function getBreeds(): Collection { return $this->breeds; }
    public function addBreed(Breed $breed): static { if (!$this->breeds->contains($breed)) { $this->breeds->add($breed); } return $this; }
    public function removeBreed(Breed $breed): static { $this->breeds->removeElement($breed); return $this; }

    /** @return Collection<int, Dog> */
    public function getDogs(): Collection { return $this->dogs; }

    /** @return Collection<int, Litter> */
    public function getLitters(): Collection { return $this->litters; }

    /** @return Collection<int, KennelPhoto> */
    public function getPhotos(): Collection { return $this->photos; }

    public function getLocation(): string
    {
        $parts = array_filter([$this->city, $this->region, $this->country]);
        return implode(', ', $parts);
    }

    public function __toString(): string { return $this->name ?? ''; }
}
