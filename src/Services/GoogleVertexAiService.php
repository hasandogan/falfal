<?php

namespace App\Services;

use Google\Cloud\AIPlatform\V1\Client\PredictionServiceClient;
use Google\Cloud\AIPlatform\V1\PredictRequest;
use Google\Protobuf\Struct;
use Google\Protobuf\Value;

class GoogleVertexAiService
{
    public function createTarot($tarotOpenAIData)
    {
        $client = new PredictionServiceClient([
            'credentials' => 'config/keys/falfal2.json',
            'apiEndpoint' => 'europe-west3-aiplatform.googleapis.com',
        ]);

        $endpoint = $client->endpointName('falfal2', 'europe-west3', '4191989235965755392');

        $predictRequest = new PredictRequest();
        $predictRequest->setEndpoint($endpoint);


        // Set instances with Struct
        $predictRequest->setInstances([
            'prompt' => new Value([
                'string_value' => 'Sen bir tarot falcısısın, sana gelen datalar ile tarot falı bak, bir medyum gibi davran, sana kullanıcı ile ilgili verdiğim datayı yorum yapmak için kullan, kartların geneli pozitif ise sorunun cevabının evet olacağını ve rastgele bir sürede gerçekleşeceğini söyle örneğin 3 ay içerisinde veya 6 ay içersinde veya 5 vakit, 6 vakit ,3 vakit gibi (Max :8 min:2 ) süre tahminlerinde bulun, kartları yorumlarken tek tek alt alta yorumla örneğin 1. **Tılsım üçlüsü** : açıklama 2. **güç**: açıklama 3. **Asa Beşlisi**: açıklama … Diye gitsin texti okunaklı ilgi çekici ve orta uzunlukta tutmaya çalış,'
            ]),
            'data' => new Value([
                'string_value' => json_encode($tarotOpenAIData)
            ])
        ]);
        $predictRequest->setParameters([
            'temperature' => 1.5,
            'tokenLimits' => 8192,
            'topP' => 0.95,
        ]);

        $response = $client->predict($predictRequest);

        foreach ($response->getPredictions() as $prediction) {
            print_r($prediction);
        }
    }
}


