<?php

namespace App\Domains\UrlShortener\RedirectResponse;

use App\Domains\UrlShortener\Entities\UrlEntity;

class SimpleRedirect implements RedirectInterface
{

    public function handle(UrlEntity $url): string
    {
        return $url->getOriginalUrl();
    }
}
