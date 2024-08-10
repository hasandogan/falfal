<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\AuthenticationService;

use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class AuthController extends AbstractController
{
    private $requestStack;
    private $jwtManager;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage,RequestStack $requestStack, JWTTokenManagerInterface $jwtManager)
    {
        parent::__construct($tokenStorage,$entityManager);
        $this->requestStack = $requestStack;
        $this->jwtManager = $jwtManager;
    }

    #[Route(
        path: 'api/register',
        name: 'app_register',
        methods: ['POST']
    )]
    public function register(request $request,AuthenticationService $authenticationService,SerializerInterface $serializer)
    {
        try {
           $user = $authenticationService->createUser($request->toArray());
           $token = $this->jwtManager->create($user);
           $jsonData = $serializer->serialize($user, 'json');
            return new JsonResponse([
                'message' => 'Kaydınız başarıyla tamamlandı! Falınıza bakabilmemiz için sadece bir adım kaldı. Şimdi içeriye girmeye hazırsınız.',
                'status' => 200,
                'token' => $token,
                'data' => json_decode($jsonData),
            ]);

        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage(), 'status' => 400]);

        }
        return new JsonResponse(['message' => "Hata oluştu", 'status' => 400]);
    }
}
