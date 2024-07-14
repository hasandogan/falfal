<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\AuthenticationService;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class AuthController extends AbstractController
{
    const HASH_KEY = "05414261116";
    private $requestStack;
    private $jwtManager;
    private $tokenStorage;

    public function __construct(RequestStack $requestStack, JWTTokenManagerInterface $jwtManager,TokenStorageInterface $tokenStorage)
    {
        $this->requestStack = $requestStack;
        $this->jwtManager = $jwtManager;
        $this->tokenStorage = $tokenStorage;

    }

    #[Route(
        path: 'api/register',
        name: 'register',
        methods: ['POST']
    )]
    public function register(request $request,AuthenticationService $authenticationService,SerializerInterface $serializer)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(204);
            exit();
        }

        if ($request->getMethod() === 'OPTIONS') {
            return new jsonResponse(['message' => 'User created successfully', 'status' => 200]);
        }
        try {
           $user = $authenticationService->createUser($request->toArray());
           $token = $this->jwtManager->create($user);
           $jsonData = $serializer->serialize($user, 'json');

        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage(), 'status' => 400]);

        }
        return new JsonResponse($jsonData, 200, ['Token' => $token], true);
    }

    #[
        Route('/api/login',
        name: 'app_profile',
        methods: ['POST'])
    ]
    public function login(Request $request,AuthenticationService $authenticationService,SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            return new JsonResponse(['message' => 'E-posta veya şifre eksik'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user = $authenticationService->finduserAuth($email,$password);
        $token = $this->jwtManager->create($user);
        $jsonData = $serializer->serialize($user, 'json');
        return new JsonResponse($jsonData, 200, ['Token' => $token], true);
    }

    #[Route('/api/profile/{id}',name: 'app_login',methods: ['POST'])]
    public function profile(int $id,Request $request, AuthenticationService $authenticationService, SerializerInterface $serializer): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $authenticationService->findUserProfile($id);

        $token = $request->headers->get('Authorization');
        if (!$token) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }
        $tokenValid = $this->verifyToken($token,$user[0]);
        if ($tokenValid === false) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }
        $jsonData = $serializer->serialize($user, 'json');

        return new JsonResponse($jsonData, 200, [], true);
    }

    public function verifyToken(string $token, UserInterface $user)
    {
        try {
            $jwtToken = new JWTUserToken(['ROLE_USER'], $user, $token);

            $decodedToken = $this->jwtManager->decode($jwtToken);
            if ($decodedToken['email'] != $user->getEmail()){
               return false;
            }
            return $decodedToken;
        } catch (JWTDecodeFailureException $e) {
            return new Throws($e->getMessage());
        }
    }


    public function getRequestStack(): RequestStack
    {
        return $this->requestStack;
    }

    public function setRequestStack(RequestStack $requestStack): void
    {
        $this->requestStack = $requestStack;
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
}
