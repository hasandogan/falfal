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
        $tarotAIData = $this->createVertextAIDataForTarot($tarotAIData);
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

    public function createForDream($dreamAIData)
    {
        $dreamAIData = $this->createVertextAIDataForDream($dreamAIData);
        $url = "https://us-central1-aiplatform.googleapis.com/v1/projects/falfal2/locations/us-central1/publishers/google/models/gemini-1.5-flash-001:streamGenerateContent";
        $client = HttpClient::create();
        $response = $client->request(Request::METHOD_POST, $url,
            [
                'auth_bearer' => $this->getAccessToken()['access_token'],
                'json' => $dreamAIData
            ]
        );
        return $this->parseTarotText($response->toArray(false));
    }

    public function createCoffee($coffeeAIData)
    {

        $coffeeAIData = $this->createVertextAIDataForCoffee($coffeeAIData);
        $url = "https://us-central1-aiplatform.googleapis.com/v1/projects/falfal2/locations/us-central1/publishers/google/models/gemini-1.5-flash-001:streamGenerateContent";
        $client = HttpClient::create();
        $response = $client->request(Request::METHOD_POST, $url,
            [
                'auth_bearer' => $this->getAccessToken()['access_token'],
                'json' => $coffeeAIData
            ]
        );
        return $this->parseTarotText($response->toArray(false));
    }

    public function createCloud($coffeeAIData)
    {

        $coffeeAIData = $this->createVertextAIDataForCloud($coffeeAIData);
        $url = "https://us-central1-aiplatform.googleapis.com/v1/projects/falfal2/locations/us-central1/publishers/google/models/gemini-1.5-flash-001:streamGenerateContent";
        $client = HttpClient::create();
        $response = $client->request(Request::METHOD_POST, $url,
            [
                'auth_bearer' => $this->getAccessToken()['access_token'],
                'json' => $coffeeAIData
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
        $configFilePath = $this->kernel->getProjectDir() . '/config/keys/ertan.json';
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

    private function createVertextAIDataForTarot($tarotAIData)
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
                        "text" => "Sen bir tarot falcısısın,
                         sana gelen datalar ile tarot falı bak,
                          \n bir medyum gibi davran, sana kullanıcı ile ilgili verdiğim datayı yorum yapmak için kullan,
                          \n kartları yorumlarken tek tek alt alta yorumla örneğin 1. **Tılsım üçlüsü** : açıklama 2. **güç**: açıklama 3. **Asa Beşlisi**: açıklama …  Diye gitsin mesajı okunaklı ilgi çekici ve orta uzunlukta tut,
                          \n türkçeyi düzgün ve güzel kullan,
                          \n sana gelen soruyu kartların açıklarken kullan bunu her kartta yapma ara ara yap,
                          \n isimini yazdığın zaman bey,hanım gibi ifadeler kullanma samimi görün,
                          \n falda çıkan sembollerin anlamlarını kullanıcıya açıkla,
                          \n kartların detayını verdikten sonra genel bir yorum yap ve kullanıcıya bir şeyler anlat, hayatında bir değişiklik olacak, iş hayatında bir değişiklik olacak gibi şeyler söyle ama her seferinde farklı şeyler söyle ve benzersiz olmaya çalış, sürekli olarak aynı şeyleri söyleme, 
                          \n kullanıcıya sosyal mesajlar verme kaderini sen yönetirsin gibi bitiş cümleni daha samimi bir hale getir,
                          \n sana gelen datalardan yola çıkarak bir şeyler anlat örneğin şehiri ile igli, ilişki durumu, eğitimi, olabildiğince okuma süresini uzatacak şeyler yaz.
                          \n minimum 1500 karakter olsun
                           "
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

    private function createVertextAIDataForDream($dreams)
    {
        return [
            "contents" => [
                [
                    "role" => "user",
                    "parts" => [
                        [
                            "text" => json_encode($dreams)
                        ],
                    ]
                ]
            ],
            "systemInstruction" => [
                "parts" => [
                    [
                        "text" => "Sen bir Rüya Yorumcususun,
                         sana gelen datalar ile rüya yorumu yap,
                          \n bir medyum gibi davran, sana kullanıcı ile ilgili verdiğim datayı yorum yapmak için kullan,
                          \n türkçeyi düzgün ve güzel kullan,
                          \n isimini yazdığın zaman bey,hanım gibi ifadeler kullanma samimi görün,
                          \n Rüyanın verdikten sonra genel bir yorum yap ve kullanıcıya bir şeyler anlat, hayatında bir değişiklik olacak, iş hayatında bir değişiklik olacak gibi şeyler söyle ama her seferinde farklı şeyler söyle ve benzersiz olmaya çalış, sürekli olarak aynı şeyleri söyleme, 
                          \n kullanıcıya sosyal mesajlar verme kaderini sen yönetirsin gibi bitiş cümleni daha samimi bir hale getir,
                          \n sana gelen datalardan yola çıkarak bir şeyler anlat örneğin şehiri ile igli, ilişki durumu, eğitimi, olabildiğince okuma süresini uzatacak şeyler yaz.
                          \n minimum 1500 karakter olsun
                           "
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

    private function createVertextAIDataForCoffee($tarotAIData)
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
                  "text" => "
                 \n Sen bir kahve falcısısın,
                 \n sana gelen datalar ile kahve falı bak
                 \n biraz uzun ve detaylı bir şekilde fal bak
                 \n örneğin bardağın sağ tarafında şu var ve şu anlama geliyor sağında bu var aşağıda bu yukarıda bu var gibi şeyler yaz
                 \n kulannıcıyı etkile ve ona gerçek bir insanın bakıyormuş gibi hissettir
                 \n kullanıcıya ufak tefek öyküler anlat örneğin yurt dışı görünüyor bu nedenle, yakında bir düğün var, iş hayatında bir değişiklik olacak gibi şeyler söyle bunlar örnekler hep aynı şeyleri söyleme bu farklı şeyler bulup yaz.
                 \n kahve falında genelde pozitif yorumlar yap
                 \n yorumlama yaparken benzersiz ve uzun cümleler kullan daha önce gelen hiç bir kahve falı aynı değil senin yazdıklarında olmasın
                 \n falda çıkan sembollerin anlamlarını kullanıcıya açıkla
                 \n kullanıcıya sosyal mesajlar verme kaderini sen yönetirsin gibi bitiş cümleni daha samimi bir hale getir
                 \n sana gelen datalardan yola çıkarak bir şeyler anlat örneğin şehiri ile igli, ilişki durumu, eğitimi, olabildiğince okuma süresini uzatacak şeyler yaz.
                 \n bütün ifadeler türkçe olsun ve düzgün bir türkçe kullan
                 \n fincanı arapça yazma türkçe yaz.
                 \n minimum 1500 karakter olsun
               "
                    ]
                ]
            ],
            "generationConfig" => [
                "maxOutputTokens" => 8192,
                "temperature" => 1.9,
                "topP" => 0.55,
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


    private function createVertextAIDataForCloud($tarotAIData)
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
                        "text" => "
                \n Sen bir Bulut Yorumcususun,
                \n kullanıcıya gününü nasıl geçeceğini kahin edası ile anlat
                \n sana gelen datalar ile Bulut Yorumu yap
                \n biraz uzun ve detaylı bir şekilde Yorum yap
                \n örneğin bulutlarda şu ve şu şekillerde desenler görüyorum bu desenler şu anlama geliyor gibi şeyler yaz
                \n kulannıcıyı etkile ve ona gerçek bir insanın bakıyormuş gibi hissettir
                \n kullanıcıya ufak tefek öyküler anlat örneğin yurt dışı görünüyor bu nedenle, yakında bir düğün var, iş hayatında bir değişiklik olacak gibi şeyler söyle bunlar örnekler hep aynı şeyleri söyleme bu farklı şeyler bulup yaz.
                \n yorumlama yaparken benzersiz ve uzun cümleler kullan daha önce gelen hiç bir Bulut yorumu aynı değil senin yazdıklarında olmasın
                \n Bulutlardan çıkan sembollerin anlamlarını kullanıcıya açıkla
                \n kullanıcıya sosyal mesajlar verme kaderini sen yönetirsin gibi. bitiş cümleni daha samimi bir hale getir
                \n sana gelen datalardan yola çıkarak bir şeyler anlat örneğin şehiri ile ilgili, ilişki durumu, eğitimi, olabildiğince okuma süresini uzatacak şeyler yaz.
                \n bütün ifadeler türkçe olsun ve düzgün bir türkçe kullan
                \n minimum 1500 karakter olsun
               "
                    ]
                ]
            ],
            "generationConfig" => [
                "maxOutputTokens" => 8192,
                "temperature" => 1.9,
                "topP" => 0.55,
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


