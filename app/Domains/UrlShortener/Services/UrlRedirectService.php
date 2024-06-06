<?php

namespace App\Domains\UrlShortener\Services;

use App\Domains\UrlShortener\CreateRedirectUrl\CreateSimpleUrl;
use App\Domains\UrlShortener\Entities\ResponseEntity;
use App\Domains\UrlShortener\Entities\UrlEntity;
use App\Domains\UrlShortener\RedirectResponse\SimpleRedirect;
use App\Domains\UrlShortener\Repositories\UrlRepositoryInterface;

class UrlRedirectService
{
    protected $urlCreator = CreateSimpleUrl::class;
    protected $urlResponse = SimpleRedirect::class;

    private  $repository;

    public function setRepository(UrlRepositoryInterface $repository): UrlRedirectService
    {
        $this->repository = $repository;
        return $this;
    }


    public function redirectUrl(UrlEntity $url): string
    {
        return (new $this->urlCreator)->handle($url);
    }




    public function handleRedirectRequest($hash): ?ResponseEntity
    {
        $existingUrl = $this->repository->findByShortenedUrl($hash);
        if (empty($existingUrl)) {
            return $existingUrl;
        }
        $response  = new ResponseEntity(to:(new $this->urlResponse)->handle($existingUrl));
        return $response;
    }
}
