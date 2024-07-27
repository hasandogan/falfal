<?php

namespace App\Controller;

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
        path: 'api/tarots',
        name: 'tarot.all',
        methods: ['GET'],
    )]
    public function allTarot(Request $request)
    {
        $tarots = $this->entityManager
            ->getRepository(TarotProcess::class)
            ->findBy(['user' => $this->getUser()->getId()]);
        $fortunes = [];
        foreach ($tarots as $tarot) {
            $fortunes[] = [
                'id' => $tarot->getId(),
                'status' => $tarot->getStatus(),
                'fortune' => $tarot->getResponse()[0]->content[0]->text->value
            ];
        }

        return new JsonResponse($fortunes);
    }

    #[Route(
        path: 'api/tarots/last',
        name: 'tarot.last',
        methods: ['GET'],
    )]
    public function lastTarot(Request $request)
    {
        /** @var TarotProcess $tarot */
        $tarot = $this->entityManager->getRepository(TarotProcess::class)
            ->findOneBy(['user' => $this->getUser()->getId()], ['id' => 'desc']);
        $fortunes = [
            'id' => $tarot->getId(),
            'status' => $tarot->getStatus(),
            'fortune' => $tarot->getResponse()[0]->content[0]->text->value
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
       $requestData = json_decode($request->getContent(),true);
        $tarotProcess = new TarotProcess();
        $tarotProcess->setUser($this->getUser());
        $tarotProcess->setStatus(TarotProcessEnum::STARTED->value);
        $tarotProcess->setQuestion($requestData["question"]);
        $tarotProcess->setSelectedCards($requestData["selectedCards"]);
        $tarotProcess->setProcessFinishTime((new \DateTime("+30 minutes")));
        $this->entityManager->persist($tarotProcess);
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'Tarot Falınız hazırlanıyor',
            'status' => 200,
            'tarot_Id' => $tarotProcess->getId()
        ]);
    }

}