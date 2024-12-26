<?php

namespace App\Command;

use App\Entity\DreamProcess;
use App\Enums\DreamProcessEnum;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'dream:finish:status',
    description: 'Rüya yorumları tamamlandığında bildirim gönderir',
)]
class DreamFinishCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;
    private Logger $pushLogger;

    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        LoggerInterface $pushLogger  // push kanalı için
    ) {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->pushLogger = $pushLogger;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->success('Dream finish Command Started');

        try {
            /** @var DreamProcess[] $dreams */
            $dreams = $this->entityManager->getRepository(DreamProcess::class)->findBy([
                "status" => DreamProcessEnum::WAITING->value
            ]);

            foreach ($dreams as $dream) {
                if ($dream->getProcessFinishTime() < new \DateTime()) {
                    $dream->setStatus(DreamProcessEnum::COMPLETED->value);
                    $this->entityManager->persist($dream);

                    try {
                        $this->sendPushNotification(
                            $dream->getFcmToken(),
                            'Rüyanızın Sırrını Çözdük!',
                            'Rüyanızın anlamı ortaya çıktı! Hemen okuyarak bilinçaltınızın size ne söylediğini keşfedin'
                        );

                        $this->pushLogger->info('Push notification sent', [
                            'dream_id' => $dream->getId(),
                            'fcm_token' => $dream->getFcmToken(),
                            'status' => 'success'
                        ]);
                    } catch (GuzzleException $e) {
                        $this->pushLogger->error('Push notification failed', [
                            'dream_id' => $dream->getId(),
                            'fcm_token' => $dream->getFcmToken(),
                            'error' => $e->getMessage()
                        ]);
                    }

                    $this->entityManager->flush();
                }
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->logger->error('Dream finish command failed', [
                'error' => $e->getMessage()
            ]);
            return Command::FAILURE;
        }
    }

    private function sendPushNotification($fcmToken, $title, $body)
    {
        $client = new Client();

        try {
            // Access token alma
            $response = $client->post('https://oauth2.googleapis.com/token', [
                'form_params' => [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => file_get_contents(getenv('GOOGLE_APPLICATION_CREDENTIALS_BUMBI'))
                ]
            ]);

            $accessToken = json_decode($response->getBody())->access_token;

            // Bildirim gönderme
            $pushResponse = $client->post('https://fcm.googleapis.com/v1/projects/falfal2-61e4e/messages:send', [
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

            return $pushResponse->getBody()->getContents();
        } catch (GuzzleException $e) {
            $this->pushLogger->error('Push notification request failed', [
                'error' => $e->getMessage(),
                'fcm_token' => $fcmToken
            ]);
            throw $e;
        }
    }
}