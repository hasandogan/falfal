<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExceptionListener
{
    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event)
    {
        $data = [
            'status' => 401,
            'message' => 'Geçersiz kimlik bilgileri, lütfen tekrar deneyin.',
        ];

        $response = new JsonResponse($data, 201);
        $event->setResponse($response);
    }
}
