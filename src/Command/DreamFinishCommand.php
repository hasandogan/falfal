<?php

namespace App\Command;

use App\Entity\DreamProcess;
use App\Enums\DreamProcessEnum;
use App\Enums\TarotProcessEnum;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'dream:finish:status',
    description: 'Tarot biten processleri finished çeker',
)]
class DreamFinishCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface        $logger
    )
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->success('Dream finish Command Started');
        /** @var DreamProcess[] $tarots */
        $dreams = $this->entityManager->getRepository(DreamProcess::class)->findBy(
            [
                "status" => DreamProcessEnum::WAITING->value
            ]
        );
        foreach ($dreams as $dream) {
            $finishedDate = $dream->getProcessFinishTime();
            if ($finishedDate < (new \DateTime())){
                $dream->setStatus(DreamProcessEnum::COMPLETED->value);
                $this->entityManager->persist($dream);
                $this->entityManager->flush();
            }
        }
        return Command::SUCCESS;
    }

}
