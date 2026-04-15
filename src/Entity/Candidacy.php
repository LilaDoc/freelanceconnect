<?php

namespace App\Entity;

use App\Repository\CandidacyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidacyRepository::class)]
class Candidacy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $motivationMessage = null;

    #[ORM\Column(type: Types::BLOB)]
    private mixed $CV = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 500)]
    private ?string $projectLink = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?CandidacyStatus $status = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?OffreMission $mission = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $freelance = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMotivationMessage(): ?string
    {
        return $this->motivationMessage;
    }

    public function setMotivationMessage(string $motivationMessage): static
    {
        $this->motivationMessage = $motivationMessage;

        return $this;
    }

    public function getCV(): mixed
    {
        return $this->CV;
    }

    public function setCV(mixed $CV): static
    {
        $this->CV = $CV;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getProjectLink(): ?string
    {
        return $this->projectLink;
    }

    public function setProjectLink(string $projectLink): static
    {
        $this->projectLink = $projectLink;

        return $this;
    }

    public function getStatus(): ?CandidacyStatus
    {
        return $this->status;
    }

    public function setStatus(?CandidacyStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getMission(): ?OffreMission
    {
        return $this->mission;
    }

    public function setMission(?OffreMission $mission): static
    {
        $this->mission = $mission;

        return $this;
    }

    public function getFreelance(): ?User
    {
        return $this->freelance;
    }

    public function setFreelance(?User $freelance): static
    {
        $this->freelance = $freelance;

        return $this;
    }
}
