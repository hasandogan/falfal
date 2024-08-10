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
                'status' => [TarotProcessEnum::STARTED, TarotProcessEnum::IN_PROGRESS]
            ]);
        if ($preparedFortune) {
            $processTime = $preparedFortune->getProcessFinishTime();
        } else {
            $processTime = null;
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
                    'date' => $fortune->getCreatedAt(),
                    'type' => 'Tarot',
                    'message' => $fortune->getResponse()[0]->content[0]->text->value
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
                'pendingProcessExpireDate' => $processTime,
                'fortunes' => $fortunes
            ],
        ]);


    }

}