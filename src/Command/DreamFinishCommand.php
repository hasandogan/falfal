<?php

namespace App\Command;

use App\Entity\DreamProcess;
use App\Enums\DreamProcessEnum;
use App\Enums\TarotProcessEnum;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use GuzzleHttp\Client;

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

    /**
     * @throws GuzzleException
     */
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
                 $this->sendPushNotification($dream->getFcmToken(), 'Rüyanızın Sırrını Çözdük!', 'Rüyanızın anlamı ortaya çıktı! 
                 Hemen okuyarak bilinçaltınızın size ne söylediğini keşfedin');

            }
        }
        return Command::SUCCESS;
    }

    /**
     * @param $fcmToken
     * @param $title
     * @param $body
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    function sendPushNotification($fcmToken, $title, $body) {
        $client = new Client();

        try {
            $response = $client->post('https://fcm.googleapis.com/fcm/send', [
                'headers' => [
                    'Authorization' => 'key=BCTin6GZTxP338MqBRJ1mX_fwEuzn-fr21t_vThi6jt2BOZaL1Z9EXxQr30x81ZQDvSjzdK2KDtGPSQmIXqZ_8c',
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'to' => $fcmToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                        'sound' => 'default'
                    ]
                ]
            ]);

            return $response->getBody()->getContents();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
