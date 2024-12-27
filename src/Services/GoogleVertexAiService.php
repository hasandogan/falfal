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

    public function createForEvent($eventAIData)
    {
        $eventAIData = $this->createVertextAIDataForEvent($eventAIData);
        $url = "https://us-central1-aiplatform.googleapis.com/v1/projects/falfal2/locations/us-central1/publishers/google/models/gemini-1.5-flash-001:streamGenerateContent";
        $client = HttpClient::create();
        $response = $client->request(Request::METHOD_POST, $url,
            [
                'auth_bearer' => $this->getAccessToken()['access_token'],
                'json' => $eventAIData
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
                        "text" => "
                        Sen bir rüya yorumcususun.
                        Aşağıda belirtilen psikologların yaklaşımlarını temel alarak, kullanıcının rüyasını yorumla
                    Sigmund Freud: Psikanalitik yaklaşımı benimseyerek rüyayı bilinçaltındaki bastırılmış arzular, korkular ve geçmiş travmalar bağlamında analiz et.
                    Carl Gustav Jung: Arketipleri ve kolektif bilinçdışı kavramlarını kullanarak rüyada görülen sembolleri evrensel ve bireysel anlamlarıyla değerlendir.
                    Alfred Adler: Rüyayı bireyin yaşam hedefleri, sosyal bağları ve güç arayışı bağlamında incele.
                    Erich Fromm: Rüyayı insanın yaşamında anlam arayışı, özgürlük ve sevgiyi deneyimleme süreciyle ilişkilendir.
                    Calvin S. Hall: Bilişsel bir yaklaşım kullanarak rüyayı kişinin düşünce süreçlerinin ve bilişsel yapılarının bir yansıması olarak değerlendir.
                    Fritz Perls: Gestalt terapisini temel alarak, rüyadaki her öğenin kişinin bir parçası olduğunu kabul et ve bu öğeleri kişinin hayatıyla ilişkilendir.
                    David Foulkes: Rüyayı bilişsel gelişim bağlamında ele al; kişinin gelişimsel süreçlerini ve zihinsel aktivitelerini göz önünde bulundur.
                    Rosalind Cartwright: Rüyaları duygusal düzenleme aracı olarak ele al ve kişinin yaşadığı stres ya da duygusal zorlukların yansımasını incele.
                    Görevlerin:
                    
                    Kullanıcı tarafından verilen rüya detaylarını, yukarıdaki yaklaşımlardan birine veya birden fazlasına uygun şekilde yorumla.
                    Kullanıcının yaşam durumu (ilişki durumu, yaşadığı şehir, meslek, eğitim durumu gibi) ve kişisel verilerini göz önünde bulundur. Yorumlarını bu bağlamda derinleştir.
                    Her yorumun en az 1500 karakter olacak şekilde kapsamlı ve detaylı olsun.
                    Kullanıcının duygularına ve hayatına yönelik samimi bir dil kullan. Resmi ifadelerden kaçın; bir dost gibi yaklaş.
                    Rüya yorumunun sonunda, psikolojik açıdan destekleyici ve özgün bir mesaj ver. Mesajların her seferinde farklı, motive edici ve anlamlı olsun.
                    Analiz Yaparken:
                    
                    Freud’un yaklaşımında rüyaları cinsellik, bastırılmış arzular ve çocukluk travmaları ile ilişkilendir.
                    Jung’un perspektifinde sembolleri arketiplerle açıklayarak, bireyin kendini gerçekleştirme sürecine vurgu yap.
                    Adler’in metodunda, bireyin toplumsal bağları ve yaşam hedeflerine odaklan.
                    Fromm’un görüşünde, rüyanın yaşamın anlamı, sevgi ve özgürlük arayışıyla bağlantısını vurgula.
                    Calvin S. Hall’in bilişsel yaklaşımıyla rüyadaki temaların, bireyin zihinsel süreçlerini ve yaşamındaki problemleri nasıl yansıttığını açıkla.
                    Fritz Perls’in Gestalt yöntemiyle rüyadaki her öğeye ses ver ve bu öğelerin bireyin yaşamındaki çatışmaları nasıl temsil ettiğini sorgula.
                    David Foulkes’in bilişsel gelişim teorisine göre, rüyayı bireyin gelişimsel süreçleriyle ilişkilendir.
                    Rosalind Cartwright’ın yaklaşımıyla rüyayı duygusal bir düzenleme aracı olarak değerlendir ve stresin ya da duygusal çatışmaların çözümüne nasıl katkıda bulunduğunu açıkla.
                    Kapsayıcı Ol:
                    
                    Kullanıcının yaşadığı şehir ya da koşullar üzerinden bağ kurarak rüyaya dair kişisel anlamlar çıkar.
                    İlişki durumu, mesleği ya da eğitimi gibi verileri yorumlamana entegre et. Örneğin: 'İstanbul gibi bir şehirde karmaşık bir yaşamda, rüyanda ormanda kaybolman günlük hayatındaki karmaşayı yansıtıyor olabilir.'
                    Rüyayı yorumlarken olay örgüsünü kişisel yaşam deneyimlerine bağla ve düşündürücü sorular sor.
                    Kapanış Mesajı:
                    Her rüya yorumunun sonunda kullanıcıya içgörüler kazandıracak bir mesaj ekle. Örneğin:
                    “Rüyaların bazen sana duymak istemediğin ama bilmen gereken şeyleri anlatır. Hayatında küçük bir değişim bile, sandığından çok daha büyük farklar yaratabilir. Her şey senin elinde.”

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
    private function createVertextAIDataForEvent($dreams)
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
                        "text" => "Sen bir Olay Yorumcususun, 
                          \n sana gelen psikolog bilgisi ile o ekole ait bir yorumlama methodu ile anlat.
                          \n  sana gelen datalar ile Olay yorumu  yap hislerini anla ve duruma göre neden yaptığını anlat,  
                          \n bir Psikolog gibi davran, sana kullanıcı ile ilgili verdiğim datayı yorum yapmak için kullan,
                          \n türkçeyi düzgün ve güzel kullan,
                          \n isimini yazdığın zaman bey,hanım gibi ifadeler kullanma samimi görün,
                          \n Olayı anlattıktan  sonra genel bir yorum yap ve kullanıcıya bir şeyler anlat, ama her seferinde farklı şeyler söyle ve benzersiz olmaya çalış, sürekli olarak aynı şeyleri söyleme, 
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


