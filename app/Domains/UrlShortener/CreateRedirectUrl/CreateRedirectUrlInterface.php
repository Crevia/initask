<?php

namespace App\Domains\UrlShortener\CreateRedirectUrl;

use App\Domains\UrlShortener\Entities\UrlEntity;

interface CreateRedirectUrlInterface
{
    public function handle(UrlEntity $url): string;
}
