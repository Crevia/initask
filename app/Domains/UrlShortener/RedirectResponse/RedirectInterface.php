<?php

namespace App\Domains\UrlShortener\RedirectResponse;

use App\Domains\UrlShortener\Entities\UrlEntity;

interface RedirectInterface
{
    public function handle(UrlEntity $url): string;
}
