<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AbstractController
{
    private TokenStorageInterface $tokenStorage;
    protected EntityManagerInterface $entityManager;

    public function __construct(
    TokenStorageInterface $tokenStorage,
    EntityManagerInterface $entityManager
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
    }


    /**
     * @return User
     */
    protected function getUser()
    {
        return $this->tokenStorage->getToken()->getUser();
    }
}
