<?php

namespace App\Command;

use App\Entity\DreamProcess;
use App\Enums\DreamProcessEnum;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Exception\GuzzleException;
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

    public function __construct(
        EntityManagerInterface $entityManager,
    )
    {
        parent::__construct();
        $this->entityManager = $entityManager;
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

        // Access token alma
        $client = new Client();
        $response = $client->post('https://oauth2.googleapis.com/token', [
            'form_params' => [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => file_get_contents(getenv('GOOGLE_APPLICATION_CREDENTIALS_BUMBI'))
            ]
        ]);

        $accessToken = json_decode($response->getBody())->access_token;

        // Bildirim gönderme
        return $client->post('https://fcm.googleapis.com/v1/projects/falfal2-61e4e/messages:send', [
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
