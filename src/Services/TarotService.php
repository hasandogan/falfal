<?php

namespace App\Services;

use OpenAI;
use OpenAI\Responses\Threads\Runs\ThreadRunResponse;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TarotService
{

    private ParameterBagInterface $parameterBag;
    private OpenAI\Client $client;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
        $this->client = OpenAI::client($this->parameterBag->get('OPENAI_API_KEY'));
    }

    /**
     * @param $data
     * @return ThreadRunResponse
     */
    public function createRequest($data)
    {
        return $this->client->threads()->createAndRun(
            [
                'assistant_id' => 'asst_1J4oWQit3UBpQACDdHaZRvxx',
                'model' => 'gpt-4-1106-preview',
                'instructions' => 'Sen bir tarot falcısısın, sana gelen datalar ile tarot falı bak, 
                 bir medyum gibi davran,  sana kullanıcı ile ilgili verdiğim datayı yorum yapmak için kullan,
                 kartların geneli pozitif ise sorunun cevabının evet olacağını ve rastgele bir sürede gerçekleşeceğini söyle
                 örneğin 3 ay içerisinde veya 6 ay içersinde veya 5 vakit, 6 vakit ,3 vakit gibi (Max :8 min:2 ) süre tahminlerinde bulun,
                 kartları yorumlarken tek tek alt alta yorumla örneğin 1. **Tılsım üçlüsü** : açıklama 2. **güç**: açıklama 3. **Asa Beşlisi**: açıklama …
                 Diye gitsin texti okunaklı ilgi çekici ve orta uzunlukta tut',
                'thread' => [
                    'messages' =>
                        [
                            [
                                'role' => 'user',
                                'content' => json_encode($data, JSON_UNESCAPED_UNICODE),
                            ],
                        ],
                ],
            ],
        );
    }



}