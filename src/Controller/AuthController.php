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
        // Google'a yönlendirme yapılırken doğru redirect_uri'yi belirtiyoruz
        $redirectResponse = $clientRegistry
            ->getClient('google')
            ->redirect(
                ['email', 'profile'],
                [
                    'redirect_uri' => 'http://localhost:3000/connect/google/check' // Redirect URI'yi buraya ekliyoruz
                ]
            );

        // Geri dönen URL'deki localhost'u değiştirme
        $redirectUrl = str_replace("localhost%2F","localhost:3000%2F", $redirectResponse->headers->get('location'));

        // Kullanıcıya JSON response ile yönlendirme URL'si dönüyoruz
        return new JsonResponse(['redirect' => $redirectUrl]);
    }

    #[Route(
        path: 'api/connect/google/check',
        name: 'connect_google_check',
        methods: ['POST']
    )]
    public function connectGoogleCheck(Request $request, ClientRegistry $clientRegistry,AuthenticationService $authenticationService)
    {
        try {


        $requestBody = json_decode($request->getContent(), false);

        // Initialize Google client
        $googleClient = $clientRegistry
            ->getClient('google')
            ->getOAuth2Provider();

            $accessToken = $googleClient->getAccessToken('authorization_code', [
                'code' => $requestBody->code,
                'redirect_uri' => 'http://localhost:3000/connect/google/check'
            ]);

            $idToken = $accessToken->getValues()['id_token']; // id_token burada yer alır

            $client = new Google_Client();
            $payload = $client->verifyIdToken($idToken);

            if ($payload) {
                // Payload contains user information
                $userId = $payload['sub']; // User's Google ID
                $email = $payload['email'];
                $name = $payload['name'];
                $nameParts = explode(' ', $name);
                $firstName = $nameParts[0]; // İlk kısım: 'Hasan'
                $lastName = isset($nameParts[1]) ? $nameParts[1] : ''; // İkinci kısım: 'Doğan'

                $data = [
                    'name' => $firstName ,
                    'lastName' => $lastName ,
                    'email' => $email
                ];

               $user =  $authenticationService->createUserForGoogle($data);
               $token = $this->jwtManager->create($user);
                return new JsonResponse([
                    'status' => 200,
                    'token' => $token,
                ]);

            } else {
                // Invalid JWT
                return new JsonResponse(['error' => 'Invalid token'], 401);
            }
        }catch (\Exception $exception){
            return new JsonResponse(['message' => $exception->getMessage(), 'status' => 400]);
        }
    }

    #[Route(
        path: 'connect/google/check',
        name: 'connect_google_check_2',
        methods: ['GET']
    )]
    public function connectGoogleCheck2()
    {
        dd('sss');
    }

}
