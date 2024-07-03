<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\userAuth;
use App\Entity\UserLimit;
use App\Request\User\RegisterRequest;
use App\Services\AuthenticationService;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    const HASH_KEY = "05414261116";
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;

    }

    #[Route(
        path: 'api/register',
        name: 'register',
        methods: ['POST']
    )]
    public function register(RegisterRequest $request,AuthenticationService $authenticationService)
    {
        $authenticationService->createUser($request->getValidatedData());
        return new JsonResponse('ok');
    }
}
