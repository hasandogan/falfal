<?php
// src/EventSubscriber/RequestSubscriber.php
namespace App\EventSubscriber;

use App\Entity\User;
use App\Entity\userAuth;
use Predis\Client;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class RequestSubscriber implements EventSubscriberInterface
{
    const HASH_KEY = "05414261116";
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    private $requestStack;
    private $serializer;

    public function __construct(
        EntityManagerInterface $entityManager,
        RequestStack $requestStack,
        SerializerInterface $serializer
    ) {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
        $this->serializer = $serializer;


    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        try {
            if ($event->getRequest()->getRequestUri() != '/api/register'){
                $hash = $event->getRequest()->headers->get('authorization');

            if ($hash == null){
                throw new \Exception("auth bos");
            }
            $decrypt = $this->decryptData($hash, self::HASH_KEY);
            $dect = json_decode($decrypt,JSON_PRETTY_PRINT,JSON_UNESCAPED_UNICODE);
            $hashCheck = $this->checkHash($hash);
            $this->checkUser(json_decode($hashCheck)->email,$hash);
            }
        }catch (\Exception $exception){
            var_dump($exception->getMessage());exit;
        }

    }
// Veriyi şifreleme
   public function encryptData($data, $key) {
        $cipher = "aes-256-cbc";
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $encrypted = openssl_encrypt($data, $cipher, $key, $options=0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

// Şifrelenmiş veriyi çözme
   public function decryptData($data, $key) {
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        $cipher = "aes-256-cbc";
        return openssl_decrypt($encrypted_data, $cipher, $key, $options=0, $iv);
    }

    public function checkUser($email,$hash)
    {
        $userData = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($userData == null && !isset($hashData)){
            throw new \Exception("Hash bulunamadı");

        }else{
            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];

            $serializer = new Serializer($normalizers, $encoders);
            $client = new Client();
            $client->set($hash,  $serializer->serialize($userData, 'json'));
        }
    }

    public function checkHash($hash)
    {
        $hashData = $this->entityManager->getRepository(UserAuth::class)->findOneBy(['hash' => $hash]);
        if ($hashData == null && !isset($hashData)){
            throw new \Exception("Hash bulunamadı");
        }
       return $this->decryptData($hash,self::HASH_KEY);

    }



}