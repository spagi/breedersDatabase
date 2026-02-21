<?php

namespace App\Entity;

use App\Repository\BreedRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BreedRepository::class)]
class Breed
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private ?string $nameCz = null;

    #[ORM\Column(length: 200)]
    private ?string $nameEn = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $nameSk = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $ficCode = null;

    #[ORM\Column(length: 50, nullable: true, name: 'breed_group')]
    private ?string $group = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $size = null; // small, medium, large, giant

    /** @var Collection<int, Dog> */
    #[ORM\OneToMany(targetEntity: Dog::class, mappedBy: 'breed')]
    private Collection $dogs;

    /** @var Collection<int, Kennel> */
    #[ORM\ManyToMany(targetEntity: Kennel::class, mappedBy: 'breeds')]
    private Collection $kennels;

    public function __construct()
    {
        $this->dogs = new ArrayCollection();
        $this->kennels = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getNameCz(): ?string { return $this->nameCz; }
    public function setNameCz(string $nameCz): static { $this->nameCz = $nameCz; return $this; }
    public function getNameEn(): ?string { return $this->nameEn; }
    public function setNameEn(string $nameEn): static { $this->nameEn = $nameEn; return $this; }
    public function getNameSk(): ?string { return $this->nameSk; }
    public function setNameSk(?string $nameSk): static { $this->nameSk = $nameSk; return $this; }
    public function getFicCode(): ?string { return $this->ficCode; }
    public function setFicCode(?string $ficCode): static { $this->ficCode = $ficCode; return $this; }
    public function getGroup(): ?string { return $this->group; }
    public function setGroup(?string $group): static { $this->group = $group; return $this; }
    public function getSize(): ?string { return $this->size; }
    public function setSize(?string $size): static { $this->size = $size; return $this; }

    public function getName(string $locale = 'cs'): string
    {
        return match($locale) {
            'sk' => $this->nameSk ?? $this->nameCz,
            'en' => $this->nameEn,
            default => $this->nameCz,
        };
    }

    /** @return Collection<int, Dog> */
    public function getDogs(): Collection { return $this->dogs; }

    /** @return Collection<int, Kennel> */
    public function getKennels(): Collection { return $this->kennels; }

    public function __toString(): string { return $this->nameCz ?? ''; }
}
