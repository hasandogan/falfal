<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class UserUpdater extends abstractController
{

    #[Route(
        path: 'api/user/update',
        name: 'userUpdate',
        methods: ['POST']
    )]
    public function userUpdate(Request $request,EntityManagerInterface $entityManager)
    {
        $userRequest = json_decode($request->getContent());
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $userRequest->email]);
        if (isset($user)){
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
            $client->set($request->headers->get('authorization'),  $serializer->serialize($user, 'json'));
        }else{
            Throw new \Exception("Kullanıcı bulunamadı");
        }

        return $this->json($user);

    }


    public function decryptData($data, $key) {
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        $cipher = "aes-256-cbc";
        return openssl_decrypt($encrypted_data, $cipher, $key, $options=0, $iv);
    }
}