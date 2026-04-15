<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $invoiceNumber = null;

    #[ORM\Column]
    private ?int $amountHT = null;

    #[ORM\Column]
    private ?int $amountTTC = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $editionDate = null;

    #[ORM\Column(length: 14)]
    private ?string $SIRETClient = null;

    #[ORM\Column(length: 14)]
    private ?string $SIRETFreelance = null;

    #[ORM\Column(length: 100)]
    private ?string $companyNameClient = null;

    #[ORM\Column(length: 100)]
    private ?string $companyNameFreelance = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nameClient = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nameFreelance = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $firstNameClient = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $firstNameFreelance = null;

    #[ORM\Column(length: 255)]
    private ?string $streetAddressClient = null;

    #[ORM\Column(length: 255)]
    private ?string $streetAddressFreelance = null;

    #[ORM\Column(length: 100)]
    private ?string $cityAddressClient = null;

    #[ORM\Column(length: 100)]
    private ?string $cityAddressFreelance = null;

    #[ORM\Column(length: 100)]
    private ?string $countryAddressClient = null;

    #[ORM\Column(length: 100)]
    private ?string $countryAddressFreelance = null;

    #[ORM\Column(length: 10)]
    private ?string $postalCodeAddressClient = null;

    #[ORM\Column(length: 10)]
    private ?string $postalCodeAddressFreelance = null;

    #[ORM\Column(length: 150)]
    private ?string $missionName = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?offreMission $mission = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $freelance = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvoiceNumber(): ?string
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(string $invoiceNumber): static
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    public function getAmountHT(): ?int
    {
        return $this->amountHT;
    }

    public function setAmountHT(int $amountHT): static
    {
        $this->amountHT = $amountHT;

        return $this;
    }

    public function getAmountTTC(): ?int
    {
        return $this->amountTTC;
    }

    public function setAmountTTC(int $amountTTC): static
    {
        $this->amountTTC = $amountTTC;

        return $this;
    }

    public function getEditionDate(): ?\DateTime
    {
        return $this->editionDate;
    }

    public function setEditionDate(\DateTime $editionDate): static
    {
        $this->editionDate = $editionDate;

        return $this;
    }

    public function getSIRETClient(): ?string
    {
        return $this->SIRETClient;
    }

    public function setSIRETClient(string $SIRETClient): static
    {
        $this->SIRETClient = $SIRETClient;

        return $this;
    }

    public function getSIRETFreelance(): ?string
    {
        return $this->SIRETFreelance;
    }

    public function setSIRETFreelance(string $SIRETFreelance): static
    {
        $this->SIRETFreelance = $SIRETFreelance;

        return $this;
    }

    public function getCompanyNameClient(): ?string
    {
        return $this->companyNameClient;
    }

    public function setCompanyNameClient(string $companyNameClient): static
    {
        $this->companyNameClient = $companyNameClient;

        return $this;
    }

    public function getCompanyNameFreelance(): ?string
    {
        return $this->companyNameFreelance;
    }

    public function setCompanyNameFreelance(string $companyNameFreelance): static
    {
        $this->companyNameFreelance = $companyNameFreelance;

        return $this;
    }

    public function getNameClient(): ?string
    {
        return $this->nameClient;
    }

    public function setNameClient(?string $nameClient): static
    {
        $this->nameClient = $nameClient;

        return $this;
    }

    public function getNameFreelance(): ?string
    {
        return $this->nameFreelance;
    }

    public function setNameFreelance(?string $nameFreelance): static
    {
        $this->nameFreelance = $nameFreelance;

        return $this;
    }

    public function getFirstNameClient(): ?string
    {
        return $this->firstNameClient;
    }

    public function setFirstNameClient(?string $firstNameClient): static
    {
        $this->firstNameClient = $firstNameClient;

        return $this;
    }

    public function getFirstNameFreelance(): ?string
    {
        return $this->firstNameFreelance;
    }

    public function setFirstNameFreelance(?string $firstNameFreelance): static
    {
        $this->firstNameFreelance = $firstNameFreelance;

        return $this;
    }

    public function getStreetAddressClient(): ?string
    {
        return $this->streetAddressClient;
    }

    public function setStreetAddressClient(string $streetAddressClient): static
    {
        $this->streetAddressClient = $streetAddressClient;

        return $this;
    }

    public function getStreetAddressFreelance(): ?string
    {
        return $this->streetAddressFreelance;
    }

    public function setStreetAddressFreelance(string $streetAddressFreelance): static
    {
        $this->streetAddressFreelance = $streetAddressFreelance;

        return $this;
    }

    public function getCityAddressClient(): ?string
    {
        return $this->cityAddressClient;
    }

    public function setCityAddressClient(string $cityAddressClient): static
    {
        $this->cityAddressClient = $cityAddressClient;

        return $this;
    }

    public function getCityAddressFreelance(): ?string
    {
        return $this->cityAddressFreelance;
    }

    public function setCityAddressFreelance(string $cityAddressFreelance): static
    {
        $this->cityAddressFreelance = $cityAddressFreelance;

        return $this;
    }

    public function getCountryAddressClient(): ?string
    {
        return $this->countryAddressClient;
    }

    public function setCountryAddressClient(string $countryAddressClient): static
    {
        $this->countryAddressClient = $countryAddressClient;

        return $this;
    }

    public function getCountryAddressFreelance(): ?string
    {
        return $this->countryAddressFreelance;
    }

    public function setCountryAddressFreelance(string $countryAddressFreelance): static
    {
        $this->countryAddressFreelance = $countryAddressFreelance;

        return $this;
    }

    public function getPostalCodeAddressClient(): ?string
    {
        return $this->postalCodeAddressClient;
    }

    public function setPostalCodeAddressClient(string $postalCodeAddressClient): static
    {
        $this->postalCodeAddressClient = $postalCodeAddressClient;

        return $this;
    }

    public function getPostalCodeAddressFreelance(): ?string
    {
        return $this->postalCodeAddressFreelance;
    }

    public function setPostalCodeAddressFreelance(string $postalCodeAddressFreelance): static
    {
        $this->postalCodeAddressFreelance = $postalCodeAddressFreelance;

        return $this;
    }

    public function getMissionName(): ?string
    {
        return $this->missionName;
    }

    public function setMissionName(string $missionName): static
    {
        $this->missionName = $missionName;

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

    public function getFreelance(): ?user
    {
        return $this->freelance;
    }

    public function setFreelance(?user $freelance): static
    {
        $this->freelance = $freelance;

        return $this;
    }

    public function getClient(): ?user
    {
        return $this->client;
    }

    public function setClient(?user $client): static
    {
        $this->client = $client;

        return $this;
    }
}
