<?php

namespace App\Entity;

use App\Repository\PuppyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PuppyRepository::class)]
class Puppy
{
    const STATUSES = [
        'available' => 'puppy.status.available',
        'reserved' => 'puppy.status.reserved',
        'sold' => 'puppy.status.sold',
        'kept' => 'puppy.status.kept',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 10)]
    private ?string $gender = null;

    #[ORM\Column(length: 50)]
    private string $status = 'available';

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $color = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoFilename = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $notes = null;

    #[ORM\ManyToOne(inversedBy: 'puppies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Litter $litter = null;

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(?string $name): static { $this->name = $name; return $this; }
    public function getGender(): ?string { return $this->gender; }
    public function setGender(string $gender): static { $this->gender = $gender; return $this; }
    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): static { $this->status = $status; return $this; }
    public function getColor(): ?string { return $this->color; }
    public function setColor(?string $color): static { $this->color = $color; return $this; }
    public function getPhotoFilename(): ?string { return $this->photoFilename; }
    public function setPhotoFilename(?string $f): static { $this->photoFilename = $f; return $this; }
    public function getNotes(): ?string { return $this->notes; }
    public function setNotes(?string $n): static { $this->notes = $n; return $this; }
    public function getLitter(): ?Litter { return $this->litter; }
    public function setLitter(?Litter $litter): static { $this->litter = $litter; return $this; }
    public function isMale(): bool { return $this->gender === 'male'; }
    public function isAvailable(): bool { return $this->status === 'available'; }
}
