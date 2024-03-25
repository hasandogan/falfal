<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\userAuth;
use App\Entity\UserLimit;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    const HASH_KEY = "05414261116";
    const TAROT_LIMIT = 2;
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;

    }

    #[Route(
        path: 'api/login',
        name: 'login',
        methods: ['POST']
    )]
    public function login(Request $request)
    {

        $client = new Client();
        $user = json_decode($client->get($request->headers->get('authorization')));
        return $this->json($user);
    }

    #[Route(
        path: 'api/register',
        name: 'register',
        methods: ['POST']
    )]
    public function register(Request $request,EntityManagerInterface $entityManager)
    {
        try {
            $content = json_decode($request->getContent());
            $userRepo = $entityManager->getRepository(User::class)->findOneBy(['email' => $content->email]);
            if ($userRepo){
                Throw new \Exception("User Already Has Register",999);
            }
            $this->getValidateObject($content);
            $user = new User();
            $user->setEmail($content->email);
            $user->setName($content->name);
            $user->setLastName($content->lastName);
            $entityManager->persist($user);
            $entityManager->flush();

            $userLimit = new UserLimit();
            $userLimit->setUser($user->getId());
            $userLimit->setTarotFortuneLimit(self::TAROT_LIMIT);
            $entityManager->persist($userLimit);
            $entityManager->flush();
        }catch (\Exception $exception){
            $exceptionArray = [
                'error' => $exception->getMessage(),
                'errorCode' => $exception->getCode()
            ];
            return new Response(json_encode($exceptionArray));
        }
         return new Response($this->getEncrypt($content,$entityManager));
    }

    public function  getValidateObject($contents){
        $validate = ['email','name','lastName'];
        foreach ($contents as $key => $content){

            if(!in_array($key,$validate)){
                throw  new \Exception("geçersiz parametre " . $key);
                }
            }

    }

    public function encryptData($data, $key) {
        $cipher = "aes-256-cbc";
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $encrypted = openssl_encrypt($data, $cipher, $key, $options=0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    private function getEncrypt(mixed $content,EntityManagerInterface $entityManager)
    {
        try {
            $letsCryptKey = [
                'email' => $content->email,
                'name' => $content->name,
                'lastName' => $content->lastName
            ];

            $hash = $this->encryptData(json_encode($letsCryptKey,JSON_UNESCAPED_UNICODE),self::HASH_KEY);
            $userAuth = new userAuth();
            $userAuth->setHash($hash);
            $entityManager->persist($userAuth);
            $entityManager->flush();
        }catch (\Exception $exception){
            return  $exception->getMessage();
        }
        return $hash;
    }


    #[Route(
        path: 'api/user',
        name: 'login',
        methods: ['POST']
    )]
    public function user(Request $request)
    {

        $client = new Client();
        $user = json_decode($client->get($request->headers->get('authorization')));
        return $this->json($user);
    }

}
