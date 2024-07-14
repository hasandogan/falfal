<?php
namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AbstractController
{
    protected JWTTokenManagerInterface $jwtManager;
    protected TokenStorageInterface $tokenStorage;

    public function __construct(JWTTokenManagerInterface $jwtManager, TokenStorageInterface $tokenStorage)
    {
        $this->jwtManager = $jwtManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function getJwtManager(): JWTTokenManagerInterface
    {
        return $this->jwtManager;
    }

    public function setJwtManager(JWTTokenManagerInterface $jwtManager): void
    {
        $this->jwtManager = $jwtManager;
    }

    public function getTokenStorage(): TokenStorageInterface
    {
        return $this->tokenStorage;
    }

    public function setTokenStorage(TokenStorageInterface $tokenStorage): void
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function verifyToken(string $token, UserInterface $user): bool
    {
        try {
            // JWTUserToken nesnesini oluştururken $user parametresini geçirin
            $jwtToken = new JWTUserToken(['ROLE_USER'], $user, $token);

            // JWTManager ile token'i doğrulayın
            $decodedToken = $this->jwtManager->decode($jwtToken);

            if ($decodedToken['email'] !== $user->getEmail()) {
                return false;
            }

            // Token doğrulaması başarılıysa devam edin
            return true;
        } catch (JWTDecodeFailureException $e) {
            return false;
        }
    }
}
