<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;

#[ORM\Entity]
class UserLimit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    #[OneToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private int $user;

    #[ORM\Column]
    private int $tarotFortuneLimit;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUser(): int
    {
        return $this->user;
    }

    public function setUser(int $user): void
    {
        $this->user = $user;
    }

    public function getTarotFortuneLimit(): int
    {
        return $this->tarotFortuneLimit;
    }

    public function setTarotFortuneLimit(int $tarotFortuneLimit): void
    {
        $this->tarotFortuneLimit = $tarotFortuneLimit;
    }





}