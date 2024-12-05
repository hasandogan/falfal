<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity]
#[HasLifecycleCallbacks]
class TarotProcess
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class, cascade: ["remove"], inversedBy: 'processes')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private User $user;

    #[ORM\Column(name: "status", length: 255)]
    private $status;

    #[ORM\Column(name: "status_message", type: "text",nullable: true)]
    private $statusMessage;

    #[ORM\Column(name: "question", type: "text")]
    private $question;

    #[ORM\Column(name: "selected_cards", type: "json", nullable: true)]
    private $selectedCards = [];

    #[ORM\Column(name: "process_finish_time", type: Types::DATETIME_MUTABLE, nullable: true)]
    private $processFinishTime;

    #[ORM\Column(name: "process_short",  nullable: true)]
    private $processShort;

    #[ORM\Column(name: "response", type: "text", nullable: true)]
    private string $response;

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

    /**
     * @return mixed
     */
    public function getStatusMessage()
    {
        return $this->statusMessage;
    }

    /**
     * @param mixed $statusMessage
     */
    public function setStatusMessage($statusMessage): void
    {
        $this->statusMessage = $statusMessage;
    }

    /**
     * @return mixed
     */
    public function getProcessShort()
    {
        return $this->processShort;
    }

    /**
     * @param mixed $processShort
     */
    public function setProcessShort($processShort): void
    {
        $this->processShort = $processShort;
    }

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param mixed $question
     */
    public function setQuestion($question): void
    {
        $this->question = $question;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getSelectedCards(): array
    {
        return $this->selectedCards;
    }

    public function setSelectedCards(array $selectedCards): void
    {
        $this->selectedCards = $selectedCards;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
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

    public function getProcesses(): Collection
    {
        return $this->processes;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getResponse(): string
    {
        return $this->response;
    }

    public function setResponse(string $response): void
    {
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return mixed
     */
    public function getProcessFinishTime()
    {
        return $this->processFinishTime;
    }

    /**
     * @param mixed $processFinishTime
     */
    public function setProcessFinishTime($processFinishTime): void
    {
        $this->processFinishTime = $processFinishTime;
    }

    public function incrementAdShortCount(): void
    {
        $this->processShort++;
    }
}