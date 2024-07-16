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

    //todo: responselar ile ilgili metodlar yqz her yerde kullanılabilecek.
    // error responseları ile ilgili succes ve error response metodları oluştur.
    //exception durumlarını logla ve json response atsın
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
}
