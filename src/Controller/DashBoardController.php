<?php

namespace App\Controller;

use App\Entity\CoffeeProcess;
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

        if (!$preparedFortune){
            $preparedFortune = $this->entityManager
                ->getRepository(CoffeeProcess::class)
                ->findOneBy([
                    'user' => $this->getUser()->getId(),
                    'status' => [TarotProcessEnum::STARTED, TarotProcessEnum::IN_PROGRESS, TarotProcessEnum::WAITING]
                ]);
        }
        if ($preparedFortune) {
            $processTime = $preparedFortune->getProcessFinishTime()->format('Y-m-d H:i:s');
            $createdAt = $preparedFortune->getCreatedAt()->format('Y-m-d H:i:s');
            $type = $preparedFortune instanceof TarotProcess ? 'Tarot' : 'Coffee';
            $id = $preparedFortune->getId();
            $shortLimit = $preparedFortune->getProcessShort();
        } else {
            $processTime = null;
            $createdAt = null;
        }

        $fortunes = [];
        $tarotFortunes = $this->entityManager
            ->getRepository(TarotProcess::class)
            ->findBy(
                [
                    'user' => $this->getUser()->getId(),
                    'status' => TarotProcessEnum::COMPLETED
                ], ['id' => 'DESC'], 5
            );
        if ($tarotFortunes){
            foreach ($tarotFortunes as $fortune){
                $fortunes[] = [
                    'id' => $fortune->getId(),
                    'date' => $fortune->getCreatedAt()->format('Y-m-d H:i:s'),
                    'type' => 'Tarot',
                    'page' => 'tarot',
                    'question' => $fortune->getQuestion(),
                    'message' => mb_substr($fortune->getResponse(), 0, 240, 'UTF-8') . '...',
                ];
            }
        }

        $coffeeFortunes = $this->entityManager
            ->getRepository(CoffeeProcess::class)
            ->findBy(
                [
                    'user' => $this->getUser()->getId(),
                    'status' => TarotProcessEnum::COMPLETED
                ], ['id' => 'DESC'], 5
            );

        if ($coffeeFortunes){
            foreach ($coffeeFortunes as $fortune){
                $fortunes[] = [
                    'id' => $fortune->getId(),
                    'date' => $fortune->getCreatedAt()->format('Y-m-d H:i:s'),
                    'type' => 'Kahve Falı',
                    'page' => 'coffee',
                    'message' => mb_substr($fortune->getResponse(), 0, 240, 'UTF-8') . '...',
                ];
            }
        }

        usort($fortunes, function ($a, $b) {
            return strtotime($b['date']) <=> strtotime($a['date']);
        });


        return new JsonResponse([
            'success' => true,
            'status' => 200,
            'message' => 'Fallarınız',
            'data' => [
                'pendingProcess' => [
                    'status' => $processTime !== null ? true : false,
                    'createAt' => $createdAt ?? null,
                    'endDate' => $processTime?? null,
                    'processShort' => $preparedFortune->getProcessShort() ?? null,
                    'serverResponseTime' => date('Y-m-d H:i:s') ?? null,
                    'type' => $type ?? null,
                    'id' => $id ?? null,
                    'shortLimit' => (int)$shortLimit ?? null,
                ],
                'fortunes' => $fortunes
            ],
        ]);


    }

}
