<?php

namespace App\Command;

use App\Entity\CoffeeProcess;
use App\Enums\CoffeeProcessEnum;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'coffee:finish:status',
    description: 'coffee biten processleri finished çeker',
)]
class CoffeeFinishCommand extends Command
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
        $io->success('Coffee Creator Command Started');
        /** @var CoffeeProcess[] $coffees */
        $coffees = $this->entityManager->getRepository(CoffeeProcess::class)->findBy(
            [
                "status" => CoffeeProcessEnum::WAITING->value
            ]
        );
        foreach ($coffees as $coffee) {
            $finishedDate = $coffee->getProcessFinishTime();
            if ($finishedDate < (new \DateTime())){
                $coffee->setStatus(CoffeeProcessEnum::COMPLETED->value);
                $this->entityManager->persist($coffee);
                $this->entityManager->flush();
            }
        }
        return Command::SUCCESS;
    }

}
