<?php

namespace App\Controller;

use App\Entity\CoffeeProcess;
use App\Entity\TarotProcess;
use App\Enums\CoffeeProcessEnum;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;


#[AsController]
class CoffeeController extends AbstractController
{




    #[Route(
        path: 'api/coffee/{id}',
        name: 'coffee.id',
        methods: ['GET'],
    )]
    public function getCoffeeForId($id)
    {
        /** @var CoffeeProcess $tarot */
        $coffee = $this->entityManager->getRepository(CoffeeProcess::class)
            ->findOneBy(['user' => $this->getUser()->getId(), 'id' => $id]);
        $fortunes = [
            'success' => true,
            'status' => 200,
            'message' => 'Kahve Falınız',
            'data' => [
                'id' => $coffee->getId(),
                'message' => $coffee->getResponse(),
                'images' => $coffee->getCoffeImages()
            ],
        ];
        return new JsonResponse($fortunes);
    }

    #[Route(
        path: 'api/coffee/process/start',
        name: 'coffee.process.start',
        methods: ['POST'],
    )]
    public function startTarotProcess(Request $request)
    {
        $readyForTarot = $this->entityManager->getRepository(CoffeeProcess::class)
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
        $tarotProcess = new CoffeeProcess();
        $tarotProcess->setUser($this->getUser());
        $tarotProcess->setStatus(CoffeeProcessEnum::STARTED->value);
        $tarotProcess->setCoffeImages($requestData);
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