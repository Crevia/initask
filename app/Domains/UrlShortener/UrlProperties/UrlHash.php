<?php

namespace App\Domains\UrlShortener\UrlProperties;

class UrlHash
{
    private string $hash;

    public function __construct(string $hash)
    {
        if (strlen($hash) !== 6 || !ctype_alnum($hash)) {
            throw new \InvalidArgumentException('Invalid URL hash');
        }
        $this->hash = $hash;
    }

    public function getValue(): string
    {
        return $this->hash;
    }
}
