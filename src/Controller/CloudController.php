<?php

namespace App\Controller;

use App\Entity\CloudProcess;
use App\Entity\TarotProcess;
use App\Enums\CoffeeProcessEnum;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;


#[AsController]
class CloudController extends AbstractController
{


    #[Route(
        path: 'api/cloud/{id}',
        name: 'cloud.id',
        methods: ['GET'],
    )]
    public function getCoffeeForId($id)
    {
        /** @var CloudProcess $tarot */
        $coffee = $this->entityManager->getRepository(CloudProcess::class)
            ->findOneBy(['user' => $this->getUser()->getId(), 'id' => $id]);
        $fortunes = [
            'success' => true,
            'status' => 200,
            'message' => 'Kahve Falınız',
            'data' => [
                'id' => $coffee->getId(),
                'message' => $coffee->getResponse(),
                'images' => $coffee->getCloudImages()
            ],
        ];
        return new JsonResponse($fortunes);
    }

    #[Route(
        path: 'api/cloud/process/start',
        name: 'cloud.process.start',
        methods: ['POST'],
    )]
    public function startTarotProcess(Request $request)
    {
        $readyForTarot = $this->entityManager->getRepository(CloudProcess::class)
            ->findBy([
                'user' => $this->getUser()->getId(),
                'status' => [CoffeeProcessEnum::STARTED, CoffeeProcessEnum::IN_PROGRESS]
            ]);

        if (!$readyForTarot){
            $readyForTarot = $this->entityManager->getRepository(TarotProcess::class)
                ->findBy([
                    'user' => $this->getUser()->getId(),
                    'status' => [CoffeeProcessEnum::STARTED, CoffeeProcessEnum::IN_PROGRESS]
                ]);
        }
        if ($readyForTarot) {
            return new JsonResponse([
                'message' => 'Zaten bir falınız var , Lütfen bitmesini bekleyin.',
                'status' => 400,
                'success' => 'false',
                'data' => '',
                ],400, ['Content-Type' => 'application/json', 'Access-Control-Allow-Origin' => '*']);

        }

        $requestData = json_decode($request->getContent(),true);
        $tarotProcess = new CloudProcess();
        $tarotProcess->setUser($this->getUser());
        $tarotProcess->setStatus(CoffeeProcessEnum::STARTED->value);
        $tarotProcess->setCloudImages($requestData);
        $tarotProcess->setProcessFinishTime((new \DateTime("+15 minutes")));
        $this->entityManager->persist($tarotProcess);
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'Tarot Falınız hazırlanıyor',
            'status' => 200,
            'tarot_Id' => $tarotProcess->getId()
        ]);
    }

}