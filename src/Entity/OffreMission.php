<?php

namespace App\Entity;

use App\Repository\OffreMissionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OffreMissionRepository::class)]
class OffreMission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $budget = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $deadline = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $language = null;

    #[ORM\Column]
    private ?bool $freelanceAssigned = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?OffreMissionStatus $status = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $client = null;

    #[ORM\ManyToOne]
    private ?User $freelanceServiceProvider = null;



    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Invoice $invoiceNumber = null;

    #[ORM\Column]
    private ?bool $hasFirstPayment = null;

    #[ORM\Column(nullable: true)]
    private ?int $firstPaymentValue = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBudget(): ?int
    {
        return $this->budget;
    }

    public function setBudget(int $budget): static
    {
        $this->budget = $budget;

        return $this;
    }

    public function getDeadline(): ?\DateTime
    {
        return $this->deadline;
    }

    public function setDeadline(\DateTime $deadline): static
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function isFreelanceAssigned(): ?bool
    {
        return $this->freelanceAssigned;
    }

    public function setFreelanceAssigned(bool $freelanceAssigned): static
    {
        $this->freelanceAssigned = $freelanceAssigned;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getStatus(): ?OffreMissionStatus
    {
        return $this->status;
    }

    public function setStatus(?OffreMissionStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getFreelanceServiceProvider(): ?User
    {
        return $this->freelanceServiceProvider;
    }

    public function setFreelanceServiceProvider(?User $freelanceServiceProvider): static
    {
        $this->freelanceServiceProvider = $freelanceServiceProvider;

        return $this;
    }

    

    public function getInvoiceNumber(): ?Invoice
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(?Invoice $invoiceNumber): static
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    public function hasFirstPayment(): ?bool
    {
        return $this->hasFirstPayment;
    }

    public function setHasFirstPayment(bool $hasFirstPayment): static
    {
        $this->hasFirstPayment = $hasFirstPayment;

        return $this;
    }

    public function getFirstPaymentValue(): ?int
    {
        return $this->firstPaymentValue;
    }

    public function setFirstPaymentValue(?int $firstPaymentValue): static
    {
        $this->firstPaymentValue = $firstPaymentValue;

        return $this;
    }
}
