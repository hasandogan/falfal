<?php

namespace App\Services;

use App\Entity\User;
use App\Entity\UserLimit;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthenticationService
{
    const TAROT_LIMIT = 2;

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
        $this->findUsageUser($requestBody['email']);
        $entity = new User();
        $entity->setEmail($requestBody['email']);
        $entity->setName($requestBody['name']);
        $entity->setLastName($requestBody['lastName']);
        $entity->setPassword($this->userPasswordHasher->hashPassword($entity, $requestBody['password']));
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        $this->createUserLimit($entity);
        return $entity;
    }

    public function createUserLimit($user)
    {
        $entity = new UserLimit();
        $entity->setUser($user->getId());
        $entity->setTarotFortuneLimit(self::TAROT_LIMIT);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function findUsageUser($email)
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
       if ($user != null) {
            return throw new \Exception('Bu Email Adresi Kullanılmaktadır. Kayıt yaptığınızı hatırlıyor musunuz?, hemen giriş yapın.');
        }

    }

    public function finduserAuth($email,$password)
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($user == null) {
            return throw new \Exception('Kullanıcı bulunamadı');
        }
        $this->userPasswordHasher->isPasswordValid($user, $password);
        if (!$this->userPasswordHasher->isPasswordValid($user, $password)) {
            return throw new \Exception('Şifre Doğru değil gibi görünüyor. Tekrar deneyin.');
        }
        return $user;

    }

    public function findUserProfile($id): User
    { // üst method ile birleştirilebilir.
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
        if ($user == null) {
            return throw new \Exception('Kullanıcı bulunamadı');
        }
        return $user;

    }

    /**
     * Kullanıcıyı e-posta ve şifre ile doğrulayarak döndürür.
     *
     * @param string $email
     * @param string $password
     * @return User|null
     */
    public function authenticateUser(string $email, string $password): ?User
    {
        // E-posta ile kullanıcıyı bul
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        // Kullanıcı bulunamazsa veya şifre doğrulanamazsa null döndür
        if (!$user || !$this->userPasswordHasher->isPasswordValid($user,$password)) {
            return null;
        }

        // Kullanıcıyı döndür
        return $user;
    }
}