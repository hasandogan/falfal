<?php

namespace App\Command;

use App\Entity\TarotProcess;
use App\Enums\TarotProcessEnum;
use Doctrine\ORM\EntityManagerInterface;
use OpenAI;
use Random\RandomException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'TarotCreatorCommand',
    description: 'Add a short description for your command',
)]
class TarotCreatorCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private KernelInterface $kernel;
    private ParameterBagInterface $parameterBag;

    public function __construct(EntityManagerInterface $entityManager, KernelInterface $kernel, ParameterBagInterface $parameterBag)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->kernel = $kernel;
        $this->parameterBag = $parameterBag;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->success('Tarot Creator Command Started');
        /** @var TarotProcess[] $tarotProcesses */
        $tarotProcesses = $this->entityManager->getRepository(TarotProcess::class)->findBy(['status' => TarotProcessEnum::STARTED]);

        foreach ($tarotProcesses as $tarotProcess) {
            $tarotOpenAIData = $this->createOpenAIData($tarotProcess);
            $tarotProcesses = $this->callOpenAI($tarotProcess, $tarotOpenAIData);

        }
        return Command::SUCCESS;
    }

    /**
     * @param TarotProcess $tarotProcess
     * @param $tarotOpenAIData
     * @return TarotProcess
     */
    private function callOpenAI(TarotProcess $tarotProcess, $tarotOpenAIData)
    {
        $client = OpenAI::client($this->parameterBag->get('OPENAI_API_KEY'));
        $response = $client->threads()->createAndRun(
            [
                'assistant_id' => 'asst_1J4oWQit3UBpQACDdHaZRvxx',
                'model' => 'gpt-4-1106-preview',
                'instructions' => 'Sen bir tarot falcısısın, sana gelen datalar ile tarot falı bak, 
                    bir medyum gibi davran,  sana kullanıcı ile ilgili verdiğim datayı yorum yapamk için kullan,
                    kartların geneli poizitif ise sorunun cevabının evet olacağını ve rastgele bir sürede gerçekleşceğini söyle
                    örneğin 3 ay içerisinde veya 6 ay içersinde veya 5 vakt, 10 vakit ,3 vakit gibi süre tahminlerinde bulun,',
                'thread' => [
                    'messages' =>
                        [
                            [
                                'role' => 'user',
                                'content' => json_encode($tarotOpenAIData, JSON_UNESCAPED_UNICODE),
                            ],
                        ],
                ],
            ],
        );
        $tarotProcess->setOpenAIThreadId($response->threadId);
        $tarotProcess->setOpenAIResponseId($response->id);
        while (in_array($response->status, ['queued', 'in_progress', 'cancelling'])) {
            sleep(1); // Wait for 1 second
            $response = $client->threads()->runs()->retrieve($response->threadId, $response->id);
        }
        if ($response->status === 'completed') {
            $messages = $client->threads()->messages()->list($response->threadId);
            $tarotProcess->setStatus(TarotProcessEnum::COMPLETED);
            $tarotProcess->setResponse($messages['data']);
        } else {
            $tarotProcess->setStatus(TarotProcessEnum::FAILED);
            $tarotProcess->setStatusMessage("Failed buraya bir şeyler bulalım");
        }
        return $tarotProcess;
    }

    /**
     * @param TarotProcess $tarotProcess
     * @return array
     */
    private function createOpenAIData(TarotProcess $tarotProcess)
    {
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
            'cart_info' => $tarotProcess->getSelectedCards()
        ];
    }
}
