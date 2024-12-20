<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Date;

#[ORM\Entity]
#[UniqueConstraint(name: "email", columns: ["email"])]
#[HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
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

    #[ORM\Column(length: 255, nullable: true)]
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

    #[ORM\Column(length: 255, nullable: true)]
    private string|null $educationLevel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private string|null $birthDate = null;


    #[ORM\Column(length: 255, nullable: true)]
    private string|null $hasChildren = null;


    #[ORM\Column(length: 255, nullable: true)]
    private string|null $occupation = null;

    #[ORM\Column(type: 'json')]
    private $roles = ['ROLE_USER'];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'boolean')]
    private $isValid = 0;
/**
    #[ORM\OneToMany(targetEntity: TarotProcess::class, mappedBy: "user")]
    private Collection $processes;
**)*/

    #[ORM\Column(name: "created_at", type: Types::DATETIME_MUTABLE, nullable: true)]
    private \DateTime $createdAt;

    #[ORM\Column(name: "updated_at", type: Types::DATETIME_MUTABLE, nullable: true)]
    private \DateTime $updatedAt;

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


    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getEducationLevel(): ?string
    {
        return $this->educationLevel;
    }

    public function setEducationLevel(?string $educationLevel): void
    {
        $this->educationLevel = $educationLevel;
    }

    public function getBirthDate(): ?string
    {
        return $this->birthDate;
    }

    public function setBirthDate(?string $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    public function getHasChildren(): ?string
    {
        return $this->hasChildren;
    }

    public function setHasChildren(?string $hasChildren): void
    {
        $this->hasChildren = $hasChildren;
    }

    public function getOccupation(): ?string
    {
        return $this->occupation;
    }

    public function setOccupation(?string $occupation): void
    {
        $this->occupation = $occupation;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTime();
        $this->setUpdatedAtValue();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTime();
    }

/**
    public function getProcesses(): Collection
    {
        return $this->processes;
    }
**/

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

}
