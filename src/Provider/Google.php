<?php

namespace App\Provider;

use League\OAuth2\Client\Provider\Google as BaseGoogleProvider;

class Google extends BaseGoogleProvider
{
    public function __construct(array $options = [], array $collaborators = [])
    {
        parent::__construct($options, $collaborators);
    }

    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }
}