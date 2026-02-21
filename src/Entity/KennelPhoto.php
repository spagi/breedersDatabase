<?php

namespace App\Entity;

use App\Repository\KennelPhotoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KennelPhotoRepository::class)]
class KennelPhoto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $filename = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $caption = null;

    #[ORM\Column]
    private int $position = 0;

    #[ORM\Column]
    private \DateTimeImmutable $uploadedAt;

    #[ORM\ManyToOne(inversedBy: 'photos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Kennel $kennel = null;

    public function __construct()
    {
        $this->uploadedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getFilename(): ?string { return $this->filename; }
    public function setFilename(string $filename): static { $this->filename = $filename; return $this; }
    public function getCaption(): ?string { return $this->caption; }
    public function setCaption(?string $caption): static { $this->caption = $caption; return $this; }
    public function getPosition(): int { return $this->position; }
    public function setPosition(int $position): static { $this->position = $position; return $this; }
    public function getUploadedAt(): \DateTimeImmutable { return $this->uploadedAt; }
    public function getKennel(): ?Kennel { return $this->kennel; }
    public function setKennel(?Kennel $kennel): static { $this->kennel = $kennel; return $this; }
}
