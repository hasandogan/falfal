<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\AuthenticationService;

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

    public function __construct(JWTTokenManagerInterface $jwtManager, TokenStorageInterface $tokenStorage,RequestStack $requestStack)
    {
        parent::__construct($jwtManager, $tokenStorage);
        $this->requestStack = $requestStack;
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
            return new JsonResponse([
                'message' => 'Kaydınız başarıyla tamamlandı! Falınıza bakabilmemiz için sadece bir adım kaldı. Şimdi içeriye girmeye hazırsınız.',
                'status' => 200,
                'token' => $token,
                'data' => json_decode($jsonData),
            ]);

        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage(), 'status' => 400]);

        }
        return null;
    }

    #[
        Route('/api/login',
        name: 'app_login',
        methods: ['POST'])
    ]
    public function login(Request $request, AuthenticationService $authenticationService,SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            return new JsonResponse(['message' => 'E-posta veya şifre eksik'], JsonResponse::HTTP_BAD_REQUEST);
        }
        try {
            $user = $authenticationService->finduserAuth($email,$password);
            $token = $this->jwtManager->create($user);
            $jsonData = $serializer->serialize($user, 'json');
            return new JsonResponse([
                'message' => 'Giriş başarılı! Hoşgeldiniz.',
                'status' => 200,
                'token' => $token,
                'data' => json_decode($jsonData),
            ]);
        }catch (\Exception $e){
            return new JsonResponse(['message' => $e->getMessage(), 'status' => 400]);
        }
    }

    #[Route('/api/profile/{id}',name: 'app_profil',methods: ['GET'])]
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

    public function getRequestStack(): RequestStack
    {
        return $this->requestStack;
    }

    public function setRequestStack(RequestStack $requestStack): void
    {
        $this->requestStack = $requestStack;
    }

}
