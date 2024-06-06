<?php

namespace App\Domains\UrlShortener\Entities;

use app\Domains\UrlShortener\UrlProperties\UrlHash;
use Illuminate\Database\Eloquent\Model;

class UrlEntity
{
    private string $originalUrl;
    private UrlHash $shortenedUrl;
    private string|null $key;

    public function __construct(string $originalUrl, UrlHash $shortenedUrl)
    {
        $this->originalUrl = $originalUrl;
        $this->shortenedUrl = $shortenedUrl;
        $this->key = null;
    }

    public function getOriginalUrl(): string
    {
        return $this->originalUrl;
    }

    public function getShortenedUrl(): UrlHash
    {
        return $this->shortenedUrl;
    }


    public function setKey(Model $model): void
    {

        $this->key = $model->getKey();

    }

    public function getKey(): null|string
    {
        return $this->key;
    }
}
