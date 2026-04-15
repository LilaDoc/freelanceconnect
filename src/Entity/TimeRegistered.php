<?php

namespace App\Entity;

use App\Repository\TimeRegisteredRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimeRegisteredRepository::class)]
class TimeRegistered
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $time = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $registeredDate = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $freelance = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?offreMission $mission = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(int $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getRegisteredDate(): ?\DateTime
    {
        return $this->registeredDate;
    }

    public function setRegisteredDate(\DateTime $registeredDate): static
    {
        $this->registeredDate = $registeredDate;

        return $this;
    }

    public function getFreelance(): ?user
    {
        return $this->freelance;
    }

    public function setFreelance(?user $freelance): static
    {
        $this->freelance = $freelance;

        return $this;
    }

    public function getMission(): ?offreMission
    {
        return $this->mission;
    }

    public function setMission(?offreMission $mission): static
    {
        $this->mission = $mission;

        return $this;
    }
}
