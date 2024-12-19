<?php

namespace App\Controller;

use App\Entity\CloudProcess;
use App\Entity\CoffeeProcess;
use App\Entity\DreamProcess;
use App\Entity\TarotProcess;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AdController extends AbstractController
{


    #[Route('/api/ad-short', name: 'adshort', methods: ['POST'])]
    public function profile(Request $request, SerializerInterface $serializer): JsonResponse
    {
        // İstekten gelen JSON verisini çöz
        $data = json_decode($request->getContent(), true);
        if (!isset($data['type']) || !isset($data['id'])) {
            return new JsonResponse(['success' => false, 'message' => 'Geçersiz istek.'], 400);
        }

        $type = $data['type'];
        $id = $data['id'];

        // İlgili işlem tablosundan kaydı bul
        $repository = match ($type) {
            'Tarot' => $this->entityManager->getRepository(TarotProcess::class),
            'Coffee' => $this->entityManager->getRepository(CoffeeProcess::class),
            'Dream' => $this->entityManager->getRepository(DreamProcess::class),
            'Cloud' => $this->entityManager->getRepository(CloudProcess::class),
            default => null,
        };

        if (!$repository) {
            return new JsonResponse(['success' => false, 'message' => 'Desteklenmeyen tür.'], 400);
        }

        $process = $repository->find($id);
        if (!$process) {
            return new JsonResponse(['success' => false, 'message' => 'İşlem bulunamadı.'], 404);
        }

        // Güncelleme için kontrol
        if ($process->getProcessShort() >= 2) {
            return new JsonResponse(['success' => false, 'message' => 'Maksimum kısaltma limitine ulaşıldı.'], 400);
        }

        // Zaman kısaltma işlemi
        $newFinishTime = (clone $process->getProcessFinishTime())->modify('-5 minutes');
        $process->setProcessFinishTime($newFinishTime);
        $process->incrementAdShortCount(); // Reklam izleme sayısını artır

        $this->entityManager->persist($process);
        $this->entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'message' => 'İşlem süresi başarıyla kısaltıldı.',
            'newFinishTime' => $newFinishTime->format('Y-m-d H:i:s'),
        ]);
    }

}