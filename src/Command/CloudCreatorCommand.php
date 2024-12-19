<?php

namespace App\Command;

use App\Entity\CloudProcess;
use App\Enums\CoffeeProcessEnum;
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
    name: 'cloud:create',
    description: 'Bulut processleri işler',
)]
class CloudCreatorCommand extends Command
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
        $io->success('Cloud Creator Command Started');
        /** @var CloudProcess[] $coffeProcess */
        $coffeProcess = $this->entityManager->getRepository(CloudProcess::class)->findBy(['status' => CoffeeProcessEnum::STARTED]);
        try {
            foreach ($coffeProcess as $tarotProcess) {
                $tarotOpenAIData = $this->createAIData($tarotProcess);
                $this->callVertexAi($tarotProcess, $tarotOpenAIData);
               // $tarotProcess = $this->callOpenAI($tarotProcess, $tarotAIData);
                $this->entityManager->persist($tarotProcess);
                $this->entityManager->flush();
            }
        } catch (\Exception $exception) {
            $this->logger->log($exception->getCode(), $exception->getMessage(), ['trace' => $exception->getTrace()]);
            $tarotProcess->setStatus(TarotProcessEnum::FAILED);
            $tarotProcess->setStatusMessage("Bu fala bakacak yetenekte bir falcı bulamadık. Kendimizi geliştiricez söz veriyoruz.");
        }
        return Command::SUCCESS;
    }

    /**
     * @param CloudProcess $coffeProcess
     * @param $tarotOpenAIData
     * @return CloudProcess
     */
    private function callVertexAi(CloudProcess $coffeProcess, $tarotOpenAIData)
    {
        $response = null;
        try {
            $response = $this->googleVertexAiService->createCloud($tarotOpenAIData);
        } catch (\Exception $exception) {
            $this->logger->log($exception->getCode(), $exception->getMessage(), ['trace' => $exception->getTrace()]);
        }

        if ($response === null) {
            $coffeProcess->setStatus(CoffeeProcessEnum::FAILED->value);
            $coffeProcess->setStatusMessage("Falınıza bakacak uygun bir falcı bulamadık. Çok ilginç bir kaderiniz olmalı.");
            $this->entityManager->persist($coffeProcess);
            $this->entityManager->flush();
            return $coffeProcess;
        }

        $coffeProcess->setResponse($response);
        $coffeProcess->setStatus(TarotProcessEnum::WAITING->value);
        $this->entityManager->persist($coffeProcess);
        $this->entityManager->flush();

        return $coffeProcess;
    }

    /**
     * @param CloudProcess $coffeeProcess
     * @return array
     */
    private function createAIData(CloudProcess $coffeeProcess)
    {

        $user = $coffeeProcess->getUser();
        return [
            'user_info' => [
                'name' => $user->getName(),
                'lastName' => $user->getLastName(),
                'gender' => $user->getGender(),
                'relationShip' => $user->getRelationShip(),
                'birthDay' => $user->getBirthTime(),
                'country' => $user->getCountry(),
                'town' => $user->getCountry(),
                'jobStatus' => $user->getJobStatus()
            ],
        ];
    }
}
