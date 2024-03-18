<?php

namespace App\Controller;

use App\Entity\Process;
use App\Entity\userTarotFortune;
use Doctrine\ORM\EntityManagerInterface;
use OpenAI;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;


#[AsController]
class TarotController extends AbstractController
{
    const JSON_FILE = 'tarot.json';

    public function getTarotReading(Request $request, EntityManagerInterface $entityManager,$processId)
    {
        $question = json_decode($request->getContent());
        $cardNumber = $this->getCardForFal();
        $json = json_decode(file_get_contents(self::JSON_FILE), true);
        $position = ['straight', 'revert'];
        $cardArray = [];
        foreach ($cardNumber as $number) {
            $cardArray[] = $json['cards'][$number][$position[rand(0, 1)]];

        }
        $client = new \Predis\Client();
        $user = $client->get($request->headers->get('authorization'));
        $this->sendOpenAi($cardArray, $question, json_decode($user), $entityManager,$processId);
    }


    protected function getCardForFal()
    {
        $array = [];
        while (count($array) < 7) {
            $dest = mt_rand(0, 77); // 0 ile 77 arasında rastgele bir sayı seç
            if (!in_array($dest, $array)) { // Eğer seçilen sayı daha önce dizide yoksa
                $array[] = $dest; // Diziyi güncelle, yeni sayıyı ekle
            }
        }
        return $array;
    }

    protected function sendOpenAi($cards, $question, $user, $entityManager,$processId)
    {

        $createData = [
            'question' => $question,
            'user_info' => [
                'name' => $user->name,
                'lastName' => $user->lastName,
                'gender' => $user->gender,
                'relationShip' => $user->relationShip,
                'birthDay' => $user->birthTime,
                'country' => $user->country,
                'town' => $user->town,
                'jobStatus' => $user->jobStatus
            ],
            'cart_info' => $cards
        ];
        $yourApiKey = $this->getParameter('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);

        $response = $client->threads()->createAndRun(
            [
                'assistant_id' => 'asst_1J4oWQit3UBpQACDdHaZRvxx',
                'model' => 'gpt-4-1106-preview',
                'instructions' => 'Sen bir tarot falcısısın, sana gelen datalar ile tarot falı bak, 
                bir medyum gibi davran,  sana kullanıcı ile ilgili verdiğim datayı yorum yapamk için kullan,
                 kartların geneli poizitif ise sorunun cevabının evet olacağını ve rastgele bir sürede gerçekleşceğini söyle,',
                'thread' => [
                    'messages' =>
                        [
                            [
                                'role' => 'user',
                                'content' => json_encode($createData, JSON_UNESCAPED_UNICODE),
                            ],
                        ],
                ],
            ],
        );


        while (in_array($response->status, ['queued', 'in_progress', 'cancelling'])) {
            sleep(1); // Wait for 1 second
            $response = $client->threads()->runs()->retrieve($response->threadId, $response->id);
        }
        if ($response->status == 'completed') {
            $messages = $client->threads()->messages()->list($response->threadId);
            $tarot = new userTarotFortune();
            $tarot->setEmail($user->email);
            $tarot->setFortune(json_encode($messages['data'], JSON_PRETTY_PRINT));
            $entityManager->persist($tarot);
            $entityManager->flush();
            $process = new Process();
            $process->setProcessId($processId);
            $process->setProcessStatus($response->status);
            $process->setProcessOwnMail($user->email);
            $process->setFortuneId($tarot->getId());
            $entityManager->persist($process);
            $entityManager->flush();
        } else {
            echo $response->status;
        }
    }

    public function getTarotfall($entityManager, $id)
    {
        if ($id != null) {
            $tarots = $entityManager->getRepository(userTarotFortune::class)->findOneBy(['id' => $id]);
        }
        $fortunes = [
            'id' => $tarots->getId(),
            'fortune' => json_decode($tarots->getFortune())[0]->content[0]->text->value
        ];
        return new Response(json_encode($fortunes));
    }

    #[Route(
        name: 'tarotforemail',
        path: 'api/tarots/email',
        methods: ['POST'],
    )]
    public function getTarotForEmail(Request $request, EntityManagerInterface $entityManager)
    {
        $client = new \Predis\Client();
        $user = $client->get($request->headers->get('authorization'));
        $email = json_decode($user)->email;
        $tarots = $entityManager->getRepository(userTarotFortune::class)->findBy(['email' => $email]);
        $fortunes = [];
        foreach ($tarots as $tarot) {
            $fortunes[] = [
                'id' => $tarot->getId(),
                'fortune' => json_decode($tarot->getFortune())[0]->content[0]->text->value
            ];
        }
        return new JsonResponse($fortunes);
    }

    #[Route(
        name: 'getTarotFalByEmail',
        path: 'api/tarots/last',
        methods: ['POST'],
    )]
    public function getTarotForUserLast(Request $request, EntityManagerInterface $entityManager)
    {
        $client = new \Predis\Client();
        $user = $client->get($request->headers->get('authorization'));
        $email = json_decode($user)->email;
        $tarots = $entityManager->getRepository(userTarotFortune::class)->findOneBy(['email' => $email], ['id' => 'desc']);
        $fortunes = [
            'id' => $tarots->getId(),
            'fortune' => json_decode($tarots->getFortune())[0]->content[0]->text->value
        ];

        return new JsonResponse($fortunes);
    }

    #[Route(
        name: 'process',
        path: 'api/process/{fortune}',
        methods: ['POST'],
    )]
    public function startProcess(Request $request, EntityManagerInterface $entityManager, string $fortune)
    {
        $processId = uniqid();
        if ($fortune == 'tarot') {
           // $this->getTarotReading($request, $entityManager,$processId);
        }
        $process = $entityManager->getRepository(Process::class)->findOneBy(['processId' => '65f6f82209acb']);
        $tarotFall = $this->getTarotfall($entityManager,$process->getFortuneId());
        dd($tarotFall);


        return $this->json($process);
    }

}