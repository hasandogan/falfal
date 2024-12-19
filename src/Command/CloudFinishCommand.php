<?php

namespace App\Command;

use App\Entity\CloudProcess;
use App\Enums\CoffeeProcessEnum;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'cloud:finish:status',
    description: 'cloud biten processleri finished çeker',
)]
class CloudFinishCommand extends Command
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
        $io->success('Cloud Creator Command Started');
        /** @var CloudProcess[] $coffees */
        $coffees = $this->entityManager->getRepository(CloudProcess::class)->findBy(
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
