<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\AuthenticationService;

use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
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
        $redirectResponse = $clientRegistry
            ->getClient('google')
            ->redirect(['email','profile'], []);
        $redirectUrl = str_replace("localhost%2F","localhost:3000%2F",$redirectResponse->headers->get('location'));
        return new JsonResponse(['redirect' => $redirectUrl]);
    }

    #[Route(
        path: 'api/connect/google/check',
        name: 'connect_google_check',
        methods: ['POST']
    )]
    public function connectGoogleCheck(Request $request,ClientRegistry $clientRegistry)
    {
        // burasını elle oluştur
        // https://github.com/thephpleague/oauth2-google
        $requestBody = json_decode($request->getContent(), false);
        $response = $clientRegistry
            ->getClient('google')
            ->getOAuth2Provider();
        dd($response);
            //->getAccessToken('authorization_code',['code'=>$requestBody->code]);
        return new JsonResponse(['redirect' => $response->getValues()]);
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
