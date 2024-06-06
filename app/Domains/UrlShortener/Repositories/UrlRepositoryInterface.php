<?php

namespace App\Domains\UrlShortener\Repositories;

use App\Domains\UrlShortener\Entities\UrlEntity;
use Illuminate\Database\Eloquent\Model;

interface UrlRepositoryInterface
{
    public function findByOriginalUrl(string $url): ?UrlEntity;
    public function findByShortenedUrl(string $hash): ?UrlEntity;
    public function save(UrlEntity $url): Model;
}
