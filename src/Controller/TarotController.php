<?php

namespace App\Controller;

use App\Entity\TarotProcess;
use App\Entity\User;
use App\Enums\TarotProcessEnum;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;


#[AsController]
class TarotController extends AbstractController
{

    public function __construct(JWTTokenManagerInterface $jwtManager, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($jwtManager, $tokenStorage);
    }


    #[Route(
        path: 'api/tarots',
        name: 'tarot.all',
        methods: ['GET'],
    )]
    public function allTarot(Request $request, EntityManagerInterface $entityManager)
    {
        $tarots = $entityManager->getRepository(TarotProcess::class)->findBy(['user' => $this->getUser()->getId()]);
        $fortunes = [];
        foreach ($tarots as $tarot) {
            $fortunes[] = [
                'id' => $tarot->getId(),
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
    public function lastTarot(Request $request, EntityManagerInterface $entityManager)
    {
        /** @var TarotProcess $tarot */
        $tarot = $entityManager->getRepository(TarotProcess::class)
            ->findOneBy(['user' => $this->getUser()->getId()], ['id' => 'desc']);
        $fortunes = [
            'id' => $tarot->getId(),
            'fortune' => $tarot->getResponse()[0]->content[0]->text->value
        ];

        return new JsonResponse($fortunes);
    }

    #[Route(
        path: 'api/process/start',
        name: 'process.start',
        methods: ['POST'],
    )]
    public function startTarotProcess(Request $request)
    {
        $requestData = $request->request->all();
       $tarotProcess = new TarotProcess();
       $tarotProcess->setUser($this->getUser());
       $tarotProcess->setStatus(TarotProcessEnum::STARTED);
       $tarotProcess->setQuestion($requestData['question']);
       $tarotProcess->setSelectedCards($requestData['selectedCards']);
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