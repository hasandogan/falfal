<?php

namespace App\Request\User;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterRequest
{
    private RequestStack $requestStack;
    private ValidatorInterface $validator;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack,ValidatorInterface $validator)
    {
        $this->requestStack = $requestStack;
        $this->validator = $validator;
        $this->validate();
    }

    private function validate()
    {
        $errors = $this->validator->validate($this->getData(),$this->rules());
        if ($errors->has(0)){
            // exception handler ile bu hatalar arayüze göre json response dönecek şekilde düzenlenecek
            throw new \Exception($errors->get(0)->getMessage(),400);
        }
    }

    private function rules()
    {
        return new Assert\Collection([
            'name' => new Assert\Type('string', 'invalid name'),
            'email' => new Assert\Type('string', 'invalid email'),
            'password' => [
                new Assert\Type('string', 'invalid password'),
               // new Assert\Range(['min' => 8, 'max' => 64]) bu yemedi
            ],
            'lastName' => new Assert\Type('string', 'invalid lastname')
        ]);
    }
    private function getData()
    {
        return json_decode($this->requestStack->getCurrentRequest()->getContent(),true);
    }

    public function getValidatedData()
    {
        return $this->getData();
    }
}