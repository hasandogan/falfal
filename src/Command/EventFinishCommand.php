<?php

namespace App\Command;

use App\Entity\DreamProcess;
use App\Entity\EventProcess;
use App\Enums\DreamProcessEnum;
use App\Enums\EventProcessEnum;
use App\Enums\TarotProcessEnum;
use Doctrine\ORM\EntityManagerInterface;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Cloud\AIPlatform\V1\Event;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'event:finish:status',
    description: 'Tarot biten processleri finished çeker',
)]
class EventFinishCommand extends Command
{
    private KernelInterface $kernel;
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    public function __construct(
        KernelInterface        $kernel,
        EntityManagerInterface $entityManager,
        LoggerInterface        $logger,
    )
    {
        $this->kernel = $kernel;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->success('Event finish Command Started');

        try {
            $events = $this->entityManager->getRepository(EventProcess::class)
                ->findBy(["status" => DreamProcessEnum::WAITING->value]);

            foreach ($events as $event) {
                if ($event->getProcessFinishTime() < new \DateTime()) {
                    $event->setStatus(EventProcessEnum::COMPLETED->value);

                    try {
                        $this->sendPushNotification(
                            $event->getFcmToken(),
                            'Olayın Sırrını Çözdük!',
                            'Olayınızın anlamı ortaya çıktı! Hemen okuyarak bilinçaltınızın size ne söylediğini keşfedin'
                        );

                    } catch (GuzzleException $e) {
                        $this->logger->error('Push notification gönderme hatası', ['error' => $e->getMessage()]);
                    }

                    $this->entityManager->persist($event);
                    $this->entityManager->flush();
                }
            }
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->logger->error('Komut hatası', ['error' => $e->getMessage()]);
            return Command::FAILURE;
        }
    }


    public function generateAccessToken()
    {
        $configFilePath = $this->kernel->getProjectDir() . '/config/keys/bumbi.json';

        $credentials = new ServiceAccountCredentials(
            ['https://www.googleapis.com/auth/firebase.messaging'],
            $configFilePath
        );
        $token = $credentials->fetchAuthToken();
        return $token['access_token'];
    }

    private function sendPushNotification(string $fcmToken, string $title, string $body)
    {
        $client = new Client();
        $accessToken = $this->generateAccessToken();
        $fcmEndpoint = "https://fcm.googleapis.com/v1/projects/falfal2-61e4e/messages:send";

        $client->post($fcmEndpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'message' => [
                    'token' => $fcmToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body
                    ]
                ]
            ]
        ]);

    }

}
