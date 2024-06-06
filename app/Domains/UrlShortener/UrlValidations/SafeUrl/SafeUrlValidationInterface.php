<?php

namespace App\Domains\UrlShortener\UrlValidations\SafeUrl;

use App\Domains\UrlShortener\UrlValidations\UrlValidationInterface;

interface SafeUrlValidationInterface extends UrlValidationInterface
{
    public function setApiKey(string $apiKey): void;
}
