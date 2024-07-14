<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\userAuth;
use App\Entity\UserLimit;
use App\Request\User\RegisterRequest;
use App\Services\AuthenticationService;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class AuthController extends AbstractController
{
    const HASH_KEY = "05414261116";
    private $requestStack;
    private $jwtManager;

    public function __construct(RequestStack $requestStack, JWTTokenManagerInterface $jwtManager)
    {
        $this->requestStack = $requestStack;
        $this->jwtManager = $jwtManager;
    }

    #[Route(
        path: 'api/register',
        name: 'register',
        methods: ['POST']
    )]
    public function register(request $request,AuthenticationService $authenticationService)
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

            return new JsonResponse([
                'message' => 'Kaydınız başarıyla tamamlandı! Falınıza bakabilmemiz için sadece bir adım kaldı. Şimdi içeriye girmeye hazırsınız.',
                'status' => 200,
                'token' => $token
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
    public function login(Request $request,AuthenticationService $authenticationService): JsonResponse
    {
        // Kullanıcı bilgilerini request'ten al
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        // Eğer e-posta veya şifre yoksa hata döndür
        if (!$email || !$password) {
            return new JsonResponse(['message' => 'E-posta veya şifre eksik'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user = $authenticationService->finduserAuth($email,$password);

        $token = $this->jwtManager->create($user);
        return new JsonResponse(['token' => $token,  'message' => 'Giriş başarılı'], JsonResponse::HTTP_OK);
    }

    #[
        Route('/api/profile/{id}',
            name: 'app_login',
            methods: ['POST'])
    ]
    public function profile(int $id,Request $request,AuthenticationService $authenticationService): JsonResponse
    {
        // to do buraya jwt kontrolü eklenmeli.
        $user = $authenticationService->findUserProfile($id);

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new
        JsonEncoder()));
        $json = $serializer->serialize($user, 'json');
        return new JsonResponse($json);
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


}
