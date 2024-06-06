<?php

namespace App\Domains\UrlShortener\Repositories;
use App\Domains\UrlShortener\Entities\UrlEntity;
use App\Domains\UrlShortener\Repositories\UrlCacheRepositoryInterface;



class UrlFastRepository implements UrlCacheRepositoryInterface
{
    private $ttl = 3600; // Default TTL is 1 hour

    protected $cache;

    public function __construct($cache)
    {
        $this->cache = $cache;
    }

    public function findByOriginalUrl(string $url): ?array
    {
       return  $this->cache::get('url:original:' . $url);

    }

    public function save(string $url, array $data): void
    {
        $this->cache::put('url:original:' . $url, $data, $this->ttl);
    }

    public function setTtl(int $ttl): UrlFastRepository
    {
        $this->ttl = $ttl;
        return $this;
    }
}
