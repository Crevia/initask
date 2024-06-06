<?php

namespace App\Domains\UrlShortener\Repositories;

use App\Domains\UrlShortener\Entities\UrlEntity;
use App\Domains\UrlShortener\Repositories\UrlRepositoryInterface;
use App\Domains\UrlShortener\UrlProperties\UrlHash;

use Illuminate\Database\Eloquent\Model;

class UrlRepository implements UrlRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function findByOriginalUrl(string $url): ?UrlEntity
    {
        $ee = null;
        $definedUrl =
            $this->model::where(['original' => $url])->first();
        if ($definedUrl) {
            $ee = new UrlEntity($definedUrl->original, new UrlHash($definedUrl->short));
            $ee->setKey($definedUrl);
        }

        return  $ee;
    }

    public function findByShortenedUrl(string $hash): ?UrlEntity
    {
        $ee = null;
        $definedUrl =
            $this->model::where(['short' => $hash])->first();
        if ($definedUrl) {
            $ee = new UrlEntity($definedUrl->original, new UrlHash($definedUrl->short));
            $ee->setKey($definedUrl);
        }

        return  $ee;
    }

    public function save(UrlEntity $url): Model
    {
        return  $this->model::create([
            'original' => $url->getOriginalUrl(),
            'short' => $url->getShortenedUrl()->getValue()
        ]);
    }
}
