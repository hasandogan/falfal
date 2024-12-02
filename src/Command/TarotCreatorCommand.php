<?php

namespace App\Command;

use App\Entity\TarotProcess;
use App\Enums\TarotProcessEnum;
use App\Services\GoogleVertexAiService;
use App\Services\TarotService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'tarot:create',
    description: 'Tarot processleri işler',
)]
class TarotCreatorCommand extends Command
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
        $io->success('Tarot Creator Command Started');
        /** @var TarotProcess[] $tarotProcesses */
        $tarotProcesses = $this->entityManager->getRepository(TarotProcess::class)->findBy(['status' => TarotProcessEnum::STARTED]);
        try {
            foreach ($tarotProcesses as $tarotProcess) {
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
     * @param TarotProcess $tarotProcess
     * @param $tarotOpenAIData
     * @return TarotProcess
     */
    private function callVertexAi(TarotProcess $tarotProcess, $tarotOpenAIData)
    {
        $response = null;
        try {
            $response = $this->googleVertexAiService->createTarot($tarotOpenAIData);
        } catch (\Exception $exception) {
            dd($exception);
            $this->logger->log($exception->getCode(), $exception->getMessage(), ['trace' => $exception->getTrace()]);
        }

        if ($response === null) {
            $tarotProcess->setStatus(TarotProcessEnum::FAILED->value);
            $tarotProcess->setStatusMessage("Falınıza bakacak uygun bir falcı bulamadık. Çok ilginç bir kaderiniz olmalı.");
            $this->entityManager->persist($tarotProcess);
            $this->entityManager->flush();
            return $tarotProcess;
        }

        $tarotProcess->setResponse($response);
        $tarotProcess->setStatus(TarotProcessEnum::WAITING->value);
        $this->entityManager->persist($tarotProcess);
        $this->entityManager->flush();

        return $tarotProcess;
    }

    /**
     * @param TarotProcess $tarotProcess
     * @return array
     */
    private function createAIData(TarotProcess $tarotProcess)
    {
        $carts = [];
        $jsonFile = 'tarot2.json';
        $tarotData = json_decode(file_get_contents($jsonFile), true);
        foreach ($tarotProcess->getSelectedCards() as $cart) {
            if ($cart['value'] === true) {
                $carts[] = $tarotData[$cart['key']-1]['front'];
            }else{
                $carts[] = $tarotData[$cart['key']-1]['revert'];

            }
        }

        $user = $tarotProcess->getUser();
        return [
            'question' => $tarotProcess->getQuestion(),
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
            'cart_info' => $carts
        ];
    }
}
