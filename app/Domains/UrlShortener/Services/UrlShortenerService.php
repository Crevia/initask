<?php

namespace App\Domains\UrlShortener\Services;

use App\Domains\UrlShortener\Entities\UrlEntity;
use App\Domains\UrlShortener\Repositories\UrlRepositoryInterface;
use App\Domains\UrlShortener\UrlProperties\UrlHash;

use App\Domains\UrlShortener\UrlValidations\UrlValidation;

class UrlShortenerService
{
    private  $repository;
    protected array $urlValidators;
    public function __construct(UrlRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function setUrlValidators(array $validators): void
    {
        $this->urlValidators = $validators;
    }

    public function shortenUrl(string $originalUrl): UrlEntity
    {
        $existingUrl = $this->repository->findByOriginalUrl($originalUrl);
        if ($existingUrl) {
            return $existingUrl;
        }

        $urlValidation = new UrlValidation($this->urlValidators);
        if (!$urlValidation->validate($originalUrl)) {
            throw new \Exception('URL is not safe.');
        }

        $shortenedUrl = new UrlHash($this->generateUniqueHash());
        $url = new UrlEntity($originalUrl, $shortenedUrl);

        if ($model = $this->repository->save($url)) {
            $url->setKey($model);
        }

        return $url;
    }

    private function generateUniqueHash(): string
    {
        do {
            $hash = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6);
        } while ($this->repository->findByShortenedUrl($hash));

        return $hash;
    }


    public function generateRedirectUrl($url) : string {

    }


    public function handleRedirectRequest($url) : string {

    }
}
