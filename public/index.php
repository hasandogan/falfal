<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';
$path = 'falfal2.json';
putenv("GOOGLE_APPLICATION_CREDENTIALS=$path");
return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
