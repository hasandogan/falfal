<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\AuthenticationService;

use Doctrine\ORM\EntityManagerInterface;
use Google_Client;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

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
    }

    #[Route(
        path: 'api/connect/google',
        name: 'connect_google',
        methods: ['GET']
    )]
    public function connectGoogle(ClientRegistry $clientRegistry)
    {
        $redirectResponse = $clientRegistry
            ->getClient('google')
            ->redirect(
                ['email', 'profile'],
                [
                    'redirect_uri' => 'http://localhost:3000/connect/google/check' // Redirect URI'yi buraya ekliyoruz
                ]
            );

        $redirectUrl = str_replace("localhost%2F","localhost:3000%2F", $redirectResponse->headers->get('location'));
        return new JsonResponse(['redirect' => $redirectUrl]);
    }

    #[Route(
        path: 'api/connect/google/check',
        name: 'connect_google_check',
        methods: ['POST']
    )]
    public function connectGoogleCheck(Request $request, ClientRegistry $clientRegistry, AuthenticationService $authenticationService)
    {
        try {
            $requestBody = json_decode($request->getContent(), false);
            $idToken = $requestBody->idToken;

            $client = new Google_Client();
            $payload = $client->verifyIdToken($idToken);

            if ($payload) {
                $email = $payload['email'];
                $name = $payload['name'];
                $nameParts = explode(' ', $name);
                $firstName = $nameParts[0];
                $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

                $data = [
                    'name' => $firstName,
                    'lastName' => $lastName,
                    'email' => $email
                ];
                $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

                if ($user) {
                    $token = $this->jwtManager->create($user);
                } else {
                    $user = $authenticationService->createUserForGoogle($data);
                    $token = $this->jwtManager->create($user);
                }
                return new JsonResponse([
                    'status' => 200,
                    'token' => $token,
                ]);
            } else {
                return new JsonResponse(['error' => 'Invalid token'], 401);
            }
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => $exception->getMessage(), 'status' => 400]);
        }
    }

    #[Route(
        path: 'api/connect/apple',
        name: 'connect_apple',
        methods: ['POST']
    )]
    public function connectToapple(Request $request, EntityManagerInterface $em)
    {
        try {
            $data = json_decode($request->getContent(), true);

            // Gelen verileri kontrol et
            if (!isset($data['apple_id'])) {
                throw new AuthenticationException('Apple ID bulunamadı');
            }

            // User repository'den apple_id ile kullanıcıyı ara
            $user = $em->getRepository(User::class)->findOneBy(['appleId' => $data['apple_id']]);

            if (!$user) {
                // Yeni kullanıcı oluştur
                $user = new User();
                $user->setAppleId($data['apple_id']);
                $user->setEmail($data['email'] ?? 'user_' . $data['apple_id'] . '@apple.signin');
                $user->setName($data['name'] ?? 'User');
                $user->setLastName($data['surname'] ?? substr($data['apple_id'], 0, 5));

                $em->persist($user);
                $em->flush();
            }

            // JWT token oluştur
            $token = $this->jwtManager->create($user);

            return new JsonResponse([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => $user->getId(),
                        'email' => $user->getEmail(),
                        'name' => $user->getName(),
                        'surname' => $user->getLastName(),
                        'apple_id' => $user->getAppleId()
                    ]
                ]
            ]);

        } catch (AuthenticationException $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ], 401);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

}
