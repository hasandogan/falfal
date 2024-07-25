<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\AuthenticationService;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Predis\Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class ProfileController extends AbstractController
{

    protected JWTTokenManagerInterface $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    #[Route('/api/profile', name: 'app_profile', methods: ['GET'])]
    public function profile(Request $request, AuthenticationService $authenticationService, SerializerInterface $serializer): JsonResponse
    {
        $token = $request->headers->get('Authorization');
        if (!$token || strpos($token, 'Bearer ') !== 0) {
            return new JsonResponse(['error' => 'Invalid token'], 401);
        }

        $token = str_replace('Bearer ', '', $token);

        try {
            $data = $this->jwtManager->parse($token);
            $userId = $data['email']; // Kullanıcı ID'sini token'dan alın

            $user = $authenticationService->findUserProfile($userId);

            if ($user === null) {
                return new JsonResponse(['error' => 'User not found'], 404);
            }

            $jsonData = $serializer->serialize($user, 'json');
            return new JsonResponse($jsonData, 200, [], true);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return new JsonResponse(['error' => 'Invalid token'], 401);
        }
    }

    #[\Symfony\Component\Routing\Annotation\Route(
        path: 'api/profile/update/{id}',
        name: 'app_profile_update',
        methods: ['POST']
    )]
    public function userUpdate(int $id, Request $request, EntityManagerInterface $entityManager)
    {
        $userRequest = json_decode($request->getContent());
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $userRequest->email]);
        if (isset($user)) {
            $user->setBirthTime($userRequest->birthTime ?? $user->getBirthTime());
            $user->setPrivacyApproved(1);
            $user->setCountry($userRequest->country ?? $user->getCountry());
            $user->setTown($userRequest->town ?? $user->getTown());
            $user->setJobStatus($userRequest->jobStatus ?? $user->getJobStatus());
            $user->setRelationShip($userRequest->relationShip ?? $user->getRelationShip());
            $user->setGender($userRequest->gender ?? $user->getGender());
            $entityManager->persist($user);
            $entityManager->flush();
            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];

            $serializer = new Serializer($normalizers, $encoders);
            $client = new Client();
            $client->set($request->headers->get('authorization'), $serializer->serialize($user, 'json'));
        } else {
            throw new \Exception("Kullanıcı bulunamadı");
        }

        return $this->json($user);

    }


}
