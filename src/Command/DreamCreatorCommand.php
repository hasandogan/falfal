<?php

namespace App\Command;

use App\Entity\DreamProcess;
use App\Entity\TarotProcess;
use App\Enums\DreamProcessEnum;
use App\Enums\TarotProcessEnum;
use App\Services\GoogleVertexAiService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'dream:create',
    description: 'Dream processleri işler',
)]
class DreamCreatorCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private KernelInterface $kernel;
    private GoogleVertexAiService $googleVertexAiService;
    private LoggerInterface $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        KernelInterface        $kernel,
        GoogleVertexAiService  $googleVertexAiService,
        LoggerInterface        $logger
    )
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->kernel = $kernel;
        $this->googleVertexAiService = $googleVertexAiService;
        $this->logger = $logger;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->success('Dream Creator Command Started');
        /** @var DreamProcess[] $dreamProcess */
        $dreamProcess = $this->entityManager->getRepository(DreamProcess::class)->findBy(['status' => DreamProcessEnum::STARTED]);
        try {
            foreach ($dreamProcess as $dreamProces) {
                $tarotOpenAIData = $this->createAIData($dreamProces);
                $this->callVertexAi($dreamProces, $tarotOpenAIData);
               // $tarotProcess = $this->callOpenAI($tarotProcess, $tarotAIData);
                $this->entityManager->persist($dreamProces);
                $this->entityManager->flush();
            }
        } catch (\Exception $exception) {
            $this->logger->log($exception->getCode(), $exception->getMessage(), ['trace' => $exception->getTrace()]);
            $dreamProces->setStatus(TarotProcessEnum::FAILED);
            $dreamProces->setStatusMessage("Bu fala bakacak yetenekte bir falcı bulamadık. Kendimizi geliştiricez söz veriyoruz.");
        }
        return Command::SUCCESS;
    }

    /**
     * @param DreamProcess $dreamProcess
     * @param $dreamOpenAIData
     * @return DreamProcess
     */
    private function callVertexAi(DreamProcess $dreamProcess, $dreamOpenAIData)
    {
        $response = null;
        try {
            $response = $this->googleVertexAiService->createForDream($dreamOpenAIData);
        } catch (\Exception $exception) {
            $this->logger->log($exception->getCode(), $exception->getMessage(), ['trace' => $exception->getTrace()]);
        }

        if ($response === null) {
            $dreamProcess->setStatus(TarotProcessEnum::FAILED->value);
            $dreamProcess->setStatusMessage("Falınıza bakacak uygun bir falcı bulamadık. Çok ilginç bir kaderiniz olmalı.");
            $this->entityManager->persist($dreamProcess);
            $this->entityManager->flush();
            return $dreamProcess;
        }

        $dreamProcess->setResponse($response);
        $dreamProcess->setStatus(DreamProcessEnum::WAITING->value);
        $this->entityManager->persist($dreamProcess);
        $this->entityManager->flush();

        return $dreamProcess;
    }

    /**
     * @param DreamProcess $coffeeProcess
     * @return array
     */
    private function createAIData(DreamProcess $dreamsProcess)
    {

        $user = $dreamsProcess->getUser();
        return [
            'user_info' => [
                'name' => $user->getName(),
                'lastName' => $user->getLastName(),
                'gender' => $user->getGender(),
                'dream' => $dreamsProcess->getDreams(),
                'relationShip' => $user->getRelationShip(),
                'birthDay' => $user->getBirthTime(),
                'country' => $user->getCountry(),
                'town' => $user->getCountry(),
                'jobStatus' => $user->getJobStatus()
            ],
        ];
    }
}
