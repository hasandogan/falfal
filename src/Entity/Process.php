<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Process
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private $processId;

    #[ORM\Column(length: 255)]
    private $processStatus;

    #[ORM\Column(length: 255)]
    private $processOwnMail;

    #[ORM\Column(length: 255)]
    private $fortuneId;

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
    public function getProcessId()
    {
        return $this->processId;
    }

    /**
     * @param mixed $processId
     */
    public function setProcessId($processId): void
    {
        $this->processId = $processId;
    }

    /**
     * @return mixed
     */
    public function getProcessStatus()
    {
        return $this->processStatus;
    }

    /**
     * @param mixed $processStatus
     */
    public function setProcessStatus($processStatus): void
    {
        $this->processStatus = $processStatus;
    }

    /**
     * @return mixed
     */
    public function getProcessOwnMail()
    {
        return $this->processOwnMail;
    }

    /**
     * @param mixed $processOwnMail
     */
    public function setProcessOwnMail($processOwnMail): void
    {
        $this->processOwnMail = $processOwnMail;
    }

    /**
     * @return mixed
     */
    public function getFortuneId()
    {
        return $this->fortuneId;
    }

    /**
     * @param mixed $fortuneId
     */
    public function setFortuneId($fortuneId): void
    {
        $this->fortuneId = $fortuneId;
    }




}