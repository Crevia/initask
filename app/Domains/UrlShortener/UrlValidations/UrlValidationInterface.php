<?php

namespace App\Domains\UrlShortener\UrlValidations;

interface UrlValidationInterface
{
    public function validate(string $url): bool;
}
