<?php
namespace App\Command;

use App\Entity\DreamProcess;
use App\Enums\DreamProcessEnum;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'dream:finish:status')]
class DreamFinishCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->success('Dream finish Command Started');

        try {
            $dreams = $this->entityManager->getRepository(DreamProcess::class)
                ->findBy(["status" => DreamProcessEnum::WAITING->value]);

            foreach ($dreams as $dream) {
                if ($dream->getProcessFinishTime() < new \DateTime()) {
                    $dream->setStatus(DreamProcessEnum::COMPLETED->value);

                    try {
                        $response = $this->sendPushNotification(
                            $dream->getFcmToken(),
                            'Rüyanızın Sırrını Çözdük!',
                            'Rüyanızın anlamı ortaya çıktı! Hemen okuyarak bilinçaltınızın size ne söylediğini keşfedin'
                        );

                        $this->logger->info('Push bildirimi gönderildi', [
                            'dream_id' => $dream->getId(),
                            'token' => $dream->getFcmToken(),
                            'response' => $response
                        ]);
                    } catch (GuzzleException $e) {
                        $this->logger->error('Push bildirimi hatası', [
                            'dream_id' => $dream->getId(),
                            'error' => $e->getMessage()
                        ]);
                    }

                    $this->entityManager->persist($dream);
                    $this->entityManager->flush();
                }
            }
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->logger->error('Komut hatası', ['error' => $e->getMessage()]);
            return Command::FAILURE;
        }
    }

    private function sendPushNotification(string $fcmToken, string $title, string $body): string
    {
        $client = new Client();

        $tokenResponse = $client->post('https://oauth2.googleapis.com/token', [
            'form_params' => [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => file_get_contents(getenv('GOOGLE_APPLICATION_CREDENTIALS_BUMBI'))
            ]
        ]);

        $accessToken = json_decode($tokenResponse->getBody())->access_token;

        $response = $client->post('https://fcm.googleapis.com/v1/projects/falfal2-61e4e/messages:send', [
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

        return $response->getBody()->getContents();
    }
}