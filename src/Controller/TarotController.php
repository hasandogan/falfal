<?php

namespace App\Controller;

use App\Entity\Process;
use App\Entity\User;
use App\Entity\userTarotFortune;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use OpenAI;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;


#[AsController]
class TarotController extends AbstractController
{

    public function __construct(JWTTokenManagerInterface $jwtManager, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($jwtManager, $tokenStorage);
    }

    const JSON_FILE = 'tarot.json';

    public function getTarotReading(Request $request, EntityManagerInterface $entityManager, User $user)
    {
        $question = json_decode($request->getContent());
        $cardNumber = $this->getCardForFal();
        $json = json_decode(file_get_contents(self::JSON_FILE), true);
        $position = ['straight', 'revert'];
        $cardArray = [];
        foreach ($cardNumber as $number) {
            $cardArray[] = $json['cards'][$number][$position[rand(0, 1)]];

        }
       return $this->sendOpenAi($cardArray, $question, $user, $entityManager);
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

    protected function sendOpenAi($cards, $question, $user, $entityManager)
    {
        /**
         * @var User $user
         */
        $createData = [
            'question' => $question,
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
            'cart_info' => $cards
        ];
        // envden alamadım amk
        $yourApiKey = "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";

        $client = OpenAI::client($yourApiKey);

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
            $tarot->setEmail($user->getEmail());
            $tarot->setFortune(json_encode($messages['data'], JSON_PRETTY_PRINT));
            $tarot->setContext('tarot');
            $entityManager->persist($tarot);
            $entityManager->flush();
        } else {
            echo $response->status;
        }
        return $tarot;
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
        path: 'api/tarots/{email}',
        methods: ['POST'],
    )]
    public function getTarotForEmail(Request $request, EntityManagerInterface $entityManager)
    {

        $token = $request->headers->get('Authorization');
        $user = $request->getContent();
        if (!$token) {
            return new JsonResponse(['message' => 'Güvenlik sebebiyle işlem yapılamadı'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $tarots = $entityManager->getRepository(userTarotFortune::class)->findBy(['email' => json_decode($user)->email]);
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
        $token = $request->headers->get('Authorization');
        $user = $request->getContent();
        if (!$token) {
            return new JsonResponse(['message' => 'Güvenlik sebebiyle işlem yapılamadı'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $tarots = $entityManager->getRepository(userTarotFortune::class)->findOneBy(['email' => json_decode($user)->email], ['id' => 'desc']);
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
    public function startProcess(Request $request, EntityManagerInterface $entityManager, string $fortune,SerializerInterface $serializer)
    {
        $token = $request->headers->get('Authorization');
        $user = $request->getContent();
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => json_decode($user)->email]);
        if (!$token) {
            return new JsonResponse(['message' => 'Güvenlik sebebiyle işlem yapılamadı'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $verify = $this->verifyToken($token,$user);
        if ($verify && $fortune == 'tarot') {
         $tarot = $this->getTarotReading($request, $entityManager, $user);
        }
        $jsonData = $serializer->serialize($tarot, 'json');
        return new JsonResponse([
            'message' => 'Tarot Falınız hazırlanıyor',
            'status' => 200,
            'tarot_Id' => $tarot->getId()
        ]);
    }

}