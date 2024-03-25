<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthenticationService
{

    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private TokenStorageInterface $tokenStorage;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, TokenStorageInterface $tokenStorage, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->tokenStorage = $tokenStorage;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function createUser($requestBody)
    {
        $entity = new User();
        $entity->setEmail($requestBody['email']);
        $entity->setName($requestBody['name']);
        $entity->setLastName($requestBody['lastName']);
        $entity->setPassword($this->userPasswordHasher->hashPassword($entity, $requestBody['password']));
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return $entity;
    }
}