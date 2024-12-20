<?php

namespace App\Controller;

use App\Entity\TarotProcess;
use App\Enums\TarotProcessEnum;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DashBoardController extends AbstractController
{

    #[Route(
        path: 'api/dashboard',
        name: 'tarot.get.last',
        methods: ['GET'],
    )]
    public function allTarot(Request $request)
    {
        $preparedFortune = $this->entityManager
            ->getRepository(TarotProcess::class)
            ->findOneBy([
                'user' => $this->getUser()->getId(),
                'status' => [TarotProcessEnum::STARTED, TarotProcessEnum::IN_PROGRESS, TarotProcessEnum::WAITING]
            ]);
        if ($preparedFortune) {
            $processTime = $preparedFortune->getProcessFinishTime()->format('Y-m-d H:i:s');
            $createdAt = $preparedFortune->getCreatedAt()->format('Y-m-d H:i:s');
        } else {
            $processTime = null;
            $createdAt = null;
        }
        $tarotFortunes = $this->entityManager
            ->getRepository(TarotProcess::class)
            ->findBy(
                [
                    'user' => $this->getUser()->getId(),
                    'status' => TarotProcessEnum::COMPLETED
                ], ['id' => 'DESC'], 5
            );
        if ($tarotFortunes){
            $fortunes = [];
            foreach ($tarotFortunes as $fortune){
                $fortunes[] = [
                    'id' => $fortune->getId(),
                    'date' => $fortune->getCreatedAt()->format('Y-m-d H:i:s'),
                    'type' => 'Tarot',
                    'question' => $fortune->getQuestion(),
                    'message' => mb_substr($fortune->getResponse(), 0, 240, 'UTF-8') . '...',
                ];
            }
        }else{
            $fortunes = [];
        }

        return new JsonResponse([

            'success' => true,
            'status' => 200,
            'message' => 'Tarot Falınız',
            'data' => [
                'pendingProcess' => [
                    'status' => $processTime !== null ? true : false,
                    'createAt' => $createdAt ?? null,
                    'endDate' => $processTime?? null,
                    'serverResponseTime' => date('Y-m-d H:i:s') ?? null
                ],
                'fortunes' => $fortunes
            ],
        ]);


    }

}