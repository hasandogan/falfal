<?php

namespace App\Controller;

use App\Entity\CoffeeProcess;
use App\Entity\TarotProcess;
use App\Enums\TarotProcessEnum;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;


#[AsController]
class TarotController extends AbstractController
{


    #[Route(
        path: 'api/tarots/{id}',
        name: 'tarot.id',
        methods: ['GET'],
    )]
    public function getTarotForId($id)
    {
        /** @var TarotProcess $tarot */
        $tarot = $this->entityManager->getRepository(TarotProcess::class)
            ->findOneBy(['user' => $this->getUser()->getId(), 'id' => $id]);
        $fortunes = [
            'success' => true,
            'status' => 200,
            'message' => 'Tarot Falınız',
            'data' => [
                'id' => $tarot->getId(),
                'question' => $tarot->getQuestion(),
                'message' => $tarot->getResponse(),
                'selectedCards' => $tarot->getSelectedCards()
            ],
        ];

        return new JsonResponse($fortunes);
    }

    #[Route(
        path: 'api/tarot/process/start',
        name: 'tarot.process.start',
        methods: ['POST'],
    )]
    public function startTarotProcess(Request $request)
    {
        $readyForTarot = $this->entityManager->getRepository(TarotProcess::class)
            ->findBy([
                'user' => $this->getUser()->getId(),
                'status' => [TarotProcessEnum::STARTED, TarotProcessEnum::IN_PROGRESS]
            ]);

        if (!$readyForTarot){
            $readyForTarot = $this->entityManager->getRepository(CoffeeProcess::class)
                ->findBy([
                    'user' => $this->getUser()->getId(),
                    'status' => [TarotProcessEnum::STARTED, TarotProcessEnum::IN_PROGRESS]
                ]);
        }

        if ($readyForTarot) {
            return new JsonResponse([
                'message' => 'Zaten bir falınız var , Lütfen bitmesini bekleyin.',
                'status' => 400,
                'success' => 'false',
                'data' => '',
                ]);
        }

        $requestData = json_decode($request->getContent(),true);
        $tarotProcess = new TarotProcess();
        $tarotProcess->setUser($this->getUser());
        $tarotProcess->setStatus(TarotProcessEnum::STARTED->value);
        $tarotProcess->setQuestion($requestData["question"]);
        $tarotProcess->setSelectedCards($requestData["selectedTarotsCards"]);
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