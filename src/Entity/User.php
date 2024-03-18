<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[ORM\Entity]
#[UniqueConstraint(name: "email", columns: ["email"])]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255, unique: true)]
    private string $email;

    #[ORM\Column(length: 255)]
    private string $name;
    #[ORM\Column(length: 255)]
    private string $lastName;
    #[ORM\Column(length: 255,nullable: true)]
    private string|null $birthTime = null;

    #[ORM\Column(length: 255, nullable: true)]
    private int|null $privacyApproved = null;

    #[ORM\Column(length: 255, nullable: true)]
    private string|null $country = null;

    #[ORM\Column(length: 255, nullable: true)]
    private string|null $town = null;

    #[ORM\Column(length: 255, nullable: true)]
    private string|null $jobStatus = null;

    #[ORM\Column(length: 255, nullable: true)]
    private string|null $relationShip = null;

    #[ORM\Column(length: 255, nullable: true)]
    private string|null $gender = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getBirthTime(): ?string
    {
        return $this->birthTime;
    }

    public function setBirthTime(?string $birthTime): void
    {
        $this->birthTime = $birthTime;
    }

    public function getPrivacyApproved(): ?int
    {
        return $this->privacyApproved;
    }

    public function setPrivacyApproved(?int $privacyApproved): void
    {
        $this->privacyApproved = $privacyApproved;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(?string $town): void
    {
        $this->town = $town;
    }

    public function getJobStatus(): ?string
    {
        return $this->jobStatus;
    }

    public function setJobStatus(?string $jobStatus): void
    {
        $this->jobStatus = $jobStatus;
    }

    public function getRelationShip(): ?string
    {
        return $this->relationShip;
    }

    public function setRelationShip(?string $relationShip): void
    {
        $this->relationShip = $relationShip;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): void
    {
        $this->gender = $gender;
    }


}
