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
                    bir medyum gibi davran,  sana kullanıcı ile ilgili verdiğim datayı yorum yapamk için kullan,
                    kartların geneli poizitif ise sorunun cevabının evet olacağını ve rastgele bir sürede gerçekleşceğini söyle
                    örneğin 3 ay içerisinde veya 6 ay içersinde veya 5 vakt, 10 vakit ,3 vakit gibi süre tahminlerinde bulun,',
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

    /**
     * @param $threadId
     * @param $responseId
     * @return ThreadRunResponse
     */
    public function checkAndGetResponse($threadId, $responseId)
    {
        $response = $this->client->threads()->runs()->retrieve($threadId, $responseId);
        while (in_array($response->status, ['queued', 'in_progress', 'cancelling'])) {
            sleep(1); // Wait for 1 second
            $response = $this->client->threads()->runs()->retrieve($threadId, $responseId);
        }
        return $response;
    }

    /**
     * @param ThreadRunResponse $response
     * @return OpenAI\Responses\Threads\Messages\ThreadMessageListResponse
     */
    public function getResponseContent(ThreadRunResponse $response)
    {
        return $this->client->threads()->messages()->list($response->threadId);
    }
}