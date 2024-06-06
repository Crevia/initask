<?php

namespace App\Domains\UrlShortener\Repositories;

use App\Domain\UrlShortener\Entities\UrlEntity;

interface UrlCacheRepositoryInterface
{
    public function findByOriginalUrl(string $url): ?array;
    public function save(string $url, array $data): void;
    public function setTtl(int $ttl): UrlCacheRepositoryInterface;
}
