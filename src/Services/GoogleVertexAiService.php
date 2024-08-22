<?php

namespace App\Services;

use App\Entity\TarotProcess;
use Google\Client;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class GoogleVertexAiService
{

    private KernelInterface $kernel;
    private CacheItemPoolInterface $cacheItemPool;

    public function __construct(KernelInterface $kernel, CacheItemPoolInterface $cacheItemPool)
    {
        $this->kernel = $kernel;
        $this->cacheItemPool = $cacheItemPool;
    }

    public function createTarot($tarotAIData)
    {
        $tarotAIData = $this->createVertextAIData($tarotAIData);
        dd($tarotAIData);
        $url = "https://us-central1-aiplatform.googleapis.com/v1/projects/falfal2/locations/us-central1/publishers/google/models/gemini-1.5-flash-001:streamGenerateContent";
        $client = HttpClient::create();
        $response = $client->request(Request::METHOD_POST, $url,
            [
                'auth_bearer' => $this->getAccessToken()['access_token'],
                'json' => $tarotAIData
            ]
        );
        return $this->parseTarotText($response->toArray(false));
    }

    private function parseTarotText($responseContent)
    {
        $text = "";
        foreach ($responseContent as $content) {
            $text .= $content['candidates'][0]['content']['parts'][0]['text'];
        }
        return $text;
    }

    private function getAccessToken()
    {
        $cacheItem = $this->cacheItemPool->getItem('google.access_token');
        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }
        $configFilePath = $this->kernel->getProjectDir() . '/config/keys/falfal2.json';
        $client = new Client(); // Burasını bir parameters yaml a set edip getParameterBagden çağırmamız gerekiyor
        $client->useApplicationDefaultCredentials();
        $client->addScope('https://www.googleapis.com/auth/cloud-platform');
        $client->setAuthConfig(json_decode(file_get_contents($configFilePath), true));
        $client->fetchAccessTokenWithAssertion();
        $this->cacheItemPool->save(
            $cacheItem->set($client->getAccessToken())->expiresAfter(3000)
        );
        return $client->getAccessToken();
        /*
         * Access tokenı bir yerde tutmamız lazım,
         * Access tokenı cachelemek lazım
         * Access token expire süresine dikkat etmek lazım 3600 saniye -> 1 saat süresi vardı
         * Refresh tokenı li bir yenileme gerekiyor
         */
    }

    private function createVertextAIData($tarotAIData)
    {
        return [
            "contents" => [
                [
                    "role" => "user",
                    "parts" => [
                        [
                            "text" => json_encode($tarotAIData)
                        ],
                    ]
                ]
            ],
            "systemInstruction" => [
                "parts" => [
                    [
                        "text" => "Sen bir tarot falcısısın, sana gelen datalar ile tarot falı bak, \n bir medyum gibi davran, sana kullanıcı ile ilgili verdiğim datayı yorum yapmak için kullan,\n kartların geneli pozitif ise sorunun cevabının evet olacağını ve rastgele bir sürede gerçekleşeceğini söyle\n örneğin 3 ay içerisinde veya 6 ay içersinde veya 5 vakit, 6 vakit ,3 vakit gibi (Max :8 min:2 ) süre tahminlerinde bulun,\n kartları yorumlarken tek tek alt alta yorumla örneğin 1. **Tılsım üçlüsü** : açıklama 2. **güç**: açıklama 3. **Asa Beşlisi**: açıklama …\n Diye gitsin texti okunaklı ilgi çekici ve orta uzunlukta tut"
                    ]
                ]
            ],
            "generationConfig" => [
                "maxOutputTokens" => 8192,
                "temperature" => 1.5,
                "topP" => 0.95,
            ],
            "safetySettings" => [
                [
                    "category" => "HARM_CATEGORY_HATE_SPEECH",
                    "threshold" => "BLOCK_MEDIUM_AND_ABOVE"
                ],
                [
                    "category" => "HARM_CATEGORY_DANGEROUS_CONTENT",
                    "threshold" => "BLOCK_MEDIUM_AND_ABOVE"
                ],
                [
                    "category" => "HARM_CATEGORY_SEXUALLY_EXPLICIT",
                    "threshold" => "BLOCK_MEDIUM_AND_ABOVE"
                ],
                [
                    "category" => "HARM_CATEGORY_HARASSMENT",
                    "threshold" => "BLOCK_MEDIUM_AND_ABOVE"
                ]
            ]
        ];
    }
}


