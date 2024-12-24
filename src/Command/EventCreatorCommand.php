<?php

namespace App\Command;

use App\Entity\EventProcess;
use App\Enums\EventProcessEnum;
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
    name: 'event:create',
    description: 'Event processleri işler',
)]
class EventCreatorCommand extends Command
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
        $io->success('Evet Creator Command Started');
        /** @var EventProcess[] $eventProcess */
        $eventProcess = $this->entityManager->getRepository(EventProcess::class)->findBy(['status' => EventProcessEnum::STARTED]);
        try {
            foreach ($eventProcess as $process) {
                $eventAIData = $this->createAIData($process);
                $this->callVertexAi($process, $eventAIData);
               // $tarotProcess = $this->callOpenAI($tarotProcess, $tarotAIData);
                $this->entityManager->persist($process);
                $this->entityManager->flush();
            }
        } catch (\Exception $exception) {
            $this->logger->log($exception->getCode(), $exception->getMessage(), ['trace' => $exception->getTrace()]);
            $process->setStatus(EventProcessEnum::FAILED);
            $process->setStatusMessage("Bu fala bakacak yetenekte bir falcı bulamadık. Kendimizi geliştiricez söz veriyoruz.");
        }
        return Command::SUCCESS;
    }

    /**
     * @param EventProcess $eventProcess
     * @param $eventOpenAIData
     * @return EventProcess
     */
    private function callVertexAi(EventProcess $eventProcess, $eventOpenAIData)
    {
        $response = null;
        try {
            $response = $this->googleVertexAiService->createForEvent($eventOpenAIData);
        } catch (\Exception $exception) {
            $this->logger->log($exception->getCode(), $exception->getMessage(), ['trace' => $exception->getTrace()]);
        }

        if ($response === null) {
            $eventProcess->setStatus(EventProcessEnum::FAILED->value);
            $eventProcess->setStatusMessage("Falınıza bakacak uygun bir falcı bulamadık. Çok ilginç bir kaderiniz olmalı.");
            $this->entityManager->persist($eventProcess);
            $this->entityManager->flush();
            return $eventProcess;
        }

        $eventProcess->setResponse($response);
        $eventProcess->setStatus(EventProcessEnum::WAITING->value);
        $this->entityManager->persist($eventProcess);
        $this->entityManager->flush();

        return $eventProcess;
    }

    /**
     * @param EventProcess $eventProcess
     * @return array
     */
    private function createAIData(EventProcess $eventProcess)
    {

        $user = $eventProcess->getUser();
        return [
            'user_info' => [
                'name' => $user->getName(),
                'lastName' => $user->getLastName(),
                'gender' => $user->getGender(),
                'event' => $eventProcess->getEvents(),
                'relationShip' => $user->getRelationShip(),
                'birthDay' => $user->getBirthTime(),
                'country' => $user->getCountry(),
                'town' => $user->getCountry(),
                'jobStatus' => $user->getJobStatus(),
                'psychologist' => $eventProcess->getPsychologist()
            ],
        ];
    }
}
