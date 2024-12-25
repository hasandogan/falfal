<?php

namespace App\Controller;

use App\Entity\CoffeeProcess;
use App\Entity\DreamProcess;
use App\Entity\EventProcess;
use App\Entity\TarotProcess;
use App\Enums\DreamProcessEnum;
use App\Enums\EventProcessEnum;
use Google\Cloud\AIPlatform\V1\Event;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;


#[AsController]
class EventController extends AbstractController
{


    #[Route(
        path: 'api/event/{id}',
        name: 'event.id',
        methods: ['GET'],
    )]
    public function getEventId($id)
    {
        /** @var EventProcess $event */
        $event = $this->entityManager->getRepository(EventProcess::class)
            ->findOneBy(['user' => $this->getUser()->getId(), 'id' => $id]);
        $fortunes = [
            'success' => true,
            'status' => 200,
            'message' => 'Olay Yorumunuz',
            'data' => [
                'id' => $event->getId(),
                'events' => $event->getEvents(),
                'message' => $event->getResponse(),
            ],
        ];

        return new JsonResponse($fortunes);
    }

    #[Route(
        path: 'api/event/process/start',
        name: 'event.process.start',
        methods: ['POST'],
    )]
    public function startEventProcess(Request $request)
    {
        $readyForEvent = $this->entityManager->getRepository(EventProcess::class)
            ->findBy([
                'user' => $this->getUser()->getId(),
                'status' => [EventProcessEnum::STARTED, EventProcessEnum::IN_PROGRESS]
            ]);

        if ($readyForEvent) {
            return new JsonResponse([
                'message' => 'Zaten bir olay yorumunuz var , Lütfen bitmesini bekleyin.',
                'status' => 400,
                'success' => 'false',
                'data' => '',
                ]);
        }

        $requestData = json_decode($request->getContent(),true);
        $eventProcess = new EventProcess();
        $eventProcess->setUser($this->getUser());
        $eventProcess->setStatus(DreamProcessEnum::STARTED->value);
        $eventProcess->setEvents($requestData["events"]);
        $eventProcess->setPsychologist($requestData["psychologist"]);
        $eventProcess->setProcessFinishTime((new \DateTime("+3 minutes")));
        $this->entityManager->persist($eventProcess);
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'Olay yorumunuz hazırlanıyor',
            'status' => 200,
            'tarot_Id' => $eventProcess->getId()
        ]);
    }

}