<?php

namespace App\Controller;

use App\Entity\CloudProcess;
use App\Entity\CoffeeProcess;
use App\Entity\DreamProcess;
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
        $preparedFortune = $this->findPendingProcess();

        $pendingProcess = $preparedFortune ? [
            'status' => true,
            'createAt' => $preparedFortune->getCreatedAt()->format('Y-m-d H:i:s'),
            'endDate' => $preparedFortune->getProcessFinishTime()->format('Y-m-d H:i:s'),
            'serverResponseTime' => date('Y-m-d H:i:s'),
            'type' => $this->getProcessType($preparedFortune),
            'id' => $preparedFortune->getId(),
            'shortLimit' => (int) $preparedFortune->getProcessShort(),
        ] : [
            'status' => false,
            'createAt' => null,
            'endDate' => null,
            'serverResponseTime' => date('Y-m-d H:i:s'),
            'type' => null,
            'id' => null,
            'shortLimit' => null,
        ];

        $fortunes = array_merge(
            $this->getFortuneDetails(TarotProcess::class, 'Tarot', 'tarot'),
            $this->getFortuneDetails(CoffeeProcess::class, 'Kahve Falı', 'coffee'),
            $this->getFortuneDetails(DreamProcess::class, 'Rüya Yorumu', 'dream'),
            $this->getFortuneDetails(CloudProcess::class, 'Bulut Yorumu', 'cloud')
        );

        usort($fortunes, fn($a, $b) => strtotime($b['date']) <=> strtotime($a['date']));

        return new JsonResponse([
            'success' => true,
            'status' => 200,
            'message' => 'Fallarınız',
            'data' => [
                'pendingProcess' => $pendingProcess,
                'fortunes' => $fortunes,
            ],
        ]);
    }

    private function findPendingProcess()
    {
        $processRepositories = [TarotProcess::class, CoffeeProcess::class, DreamProcess::class, CloudProcess::class];

        foreach ($processRepositories as $repository) {
            $process = $this->entityManager
                ->getRepository($repository)
                ->findOneBy([
                    'user' => $this->getUser()->getId(),
                    'status' => [
                        TarotProcessEnum::STARTED,
                        TarotProcessEnum::IN_PROGRESS,
                        TarotProcessEnum::WAITING
                    ]
                ]);

            if ($process) {
                return $process;
            }
        }

        return null;
    }

    private function getProcessType($process): string
    {
        return match (true) {
            $process instanceof TarotProcess => 'Tarot',
            $process instanceof CoffeeProcess => 'Coffee',
            $process instanceof DreamProcess => 'Dream',
            $process instanceof CloudProcess => 'Cloud',
            default => 'Unknown',
        };
    }

    private function getFortuneDetails(string $class, string $type, string $page): array
    {
        $fortunes = $this->entityManager
            ->getRepository($class)
            ->findBy([
                'user' => $this->getUser()->getId(),
                'status' => TarotProcessEnum::COMPLETED
            ], ['id' => 'DESC'], 5);

        $details = [];

        foreach ($fortunes as $fortune) {
            $details[] = [
                'id' => $fortune->getId(),
                'date' => $fortune->getCreatedAt()->format('Y-m-d H:i:s'),
                'type' => $type,
                'page' => $page,
                'message' => mb_substr($fortune->getResponse(), 0, 240, 'UTF-8') . '...',
            ];
        }

        return $details;
    }


}
