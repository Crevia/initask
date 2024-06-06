<?php

namespace App\Domains\UrlShortener\CreateRedirectUrl;


use App\Domains\UrlShortener\Entities\UrlEntity;
use InvalidArgumentException;

class CreateSimpleUrl implements CreateRedirectUrlInterface
{
    public function handle(UrlEntity $url): string
    {

        $baseUrl  = env("APP_URL");
        if (empty($baseUrl)) {
            throw new InvalidArgumentException("Invalid base Url specified");
        }
        return $this->generateUrl($baseUrl, $url->getShortenedUrl()->getValue());
    }


    protected function generateUrl($base, $path = '', $queryParams = [], $fragment = '')
    {

        $urlComponents = parse_url($base);


        if (!empty($path)) {
            $urlComponents['path'] = rtrim($urlComponents['path'] ?? '', '/') . '/' . ltrim($path, '/');
        }


        if (!empty($queryParams)) {
            $existingQueryParams = [];
            if (isset($urlComponents['query'])) {
                parse_str($urlComponents['query'], $existingQueryParams);
            }
            $queryParams = array_merge($existingQueryParams, $queryParams);
            $urlComponents['query'] = http_build_query($queryParams);
        }


        if (!empty($fragment)) {
            $urlComponents['fragment'] = ltrim($fragment, '#');
        }


        $generatedUrl = (isset($urlComponents['scheme']) ? $urlComponents['scheme'] . '://' : '')
            . (isset($urlComponents['user']) ? $urlComponents['user'] . (isset($urlComponents['pass']) ? ':' . $urlComponents['pass'] : '') . '@' : '')
            . (isset($urlComponents['host']) ? $urlComponents['host'] : '')
            . (isset($urlComponents['port']) ? ':' . $urlComponents['port'] : '')
            . (isset($urlComponents['path']) ? $urlComponents['path'] : '')
            . (isset($urlComponents['query']) ? '?' . $urlComponents['query'] : '')
            . (isset($urlComponents['fragment']) ? '#' . $urlComponents['fragment'] : '');

        return $generatedUrl;
    }
}
