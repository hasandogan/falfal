<?php

namespace App\Controller;

use App\Entity\CoffeeProcess;
use App\Entity\DreamProcess;
use App\Entity\TarotProcess;
use App\Enums\DreamProcessEnum;
use App\Enums\TarotProcessEnum;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;


#[AsController]
class DreamController extends AbstractController
{


    #[Route(
        path: 'api/dream/{id}',
        name: 'dream.id',
        methods: ['GET'],
    )]
    public function getDreamId($id)
    {
        /** @var DreamProcess $dream */
        $dream = $this->entityManager->getRepository(DreamProcess::class)
            ->findOneBy(['user' => $this->getUser()->getId(), 'id' => $id]);
        $fortunes = [
            'success' => true,
            'status' => 200,
            'message' => 'Rüya Yorumunuz',
            'data' => [
                'id' => $dream->getId(),
                'dreams' => $dream->getDreams(),
                'message' => $dream->getResponse(),
            ],
        ];

        return new JsonResponse($fortunes);
    }

    #[Route(
        path: 'api/dream/process/start',
        name: 'dream.process.start',
        methods: ['POST'],
    )]
    public function startDreamProcess(Request $request)
    {
        $readyForDream = $this->entityManager->getRepository(DreamProcess::class)
            ->findBy([
                'user' => $this->getUser()->getId(),
                'status' => [DreamProcessEnum::STARTED, DreamProcessEnum::IN_PROGRESS]
            ]);

        if (!$readyForDream){
            $readyForDream = $this->entityManager->getRepository(CoffeeProcess::class)
                ->findBy([
                    'user' => $this->getUser()->getId(),
                    'status' => [DreamProcessEnum::STARTED, DreamProcessEnum::IN_PROGRESS]
                ]);
            if (!$readyForDream){
                $readyForDream = $this->entityManager->getRepository(TarotProcess::class)
                    ->findBy([
                        'user' => $this->getUser()->getId(),
                        'status' => [DreamProcessEnum::STARTED, DreamProcessEnum::IN_PROGRESS]
                    ]);
            }
        }

        if ($readyForDream) {
            return new JsonResponse([
                'message' => 'Zaten bir falınız var , Lütfen bitmesini bekleyin.',
                'status' => 400,
                'success' => 'false',
                'data' => '',
                ]);
        }

        $requestData = json_decode($request->getContent(),true);
        $dreamProcess = new DreamProcess();
        $dreamProcess->setUser($this->getUser());
        $dreamProcess->setStatus(DreamProcessEnum::STARTED->value);
        $dreamProcess->setDreams($requestData["dreams"]);
        $dreamProcess->setProcessFinishTime((new \DateTime("+15 minutes")));
        $this->entityManager->persist($dreamProcess);
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'Rüya yorumunuz hazırlanıyor',
            'status' => 200,
            'tarot_Id' => $dreamProcess->getId()
        ]);
    }

}