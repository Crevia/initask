<?php

namespace App\Domains\UrlShortener\UrlValidations\SafeUrl;

use App\Domains\UrlShortener\Repositories\UrlCacheRepositoryInterface;
use App\Domains\UrlShortener\Repositories\UrlRepositoryInterface;
use InvalidArgumentException;

class  GoogleSafeUrlValidation implements SafeUrlValidationInterface
{
    private $repository;
    private $apiKey;
    private $apiEndpoint = 'https://safebrowsing.googleapis.com/v4/threatMatches:find';
    private $client = [
        'clientId' => 'intuswindows',
        'clientVersion' => '1.0.0'
    ];
    private $threatTypes = ['MALWARE', 'SOCIAL_ENGINEERING'];
    private $platformTypes = ['ANY_PLATFORM'];


    public function setClient(string $clientId, string $clientVersion): void
    {
        $this->client['clientId'] = $clientId;
        $this->client['clientVersion'] = $clientVersion;
    }

    public function setThreatTypes(array $threatTypes): void
    {
        $this->threatTypes = $threatTypes;
    }

    public function setPlatformTypes(array $platformTypes): void
    {
        $this->platformTypes = $platformTypes;
    }
    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    public function setRepository(UrlCacheRepositoryInterface $repository): GoogleSafeUrlValidation
    {
        $this->repository = $repository;
        return $this;
    }

    public function validate(string $url): bool
    {
        if (empty($this->apiKey)) {
            throw new InvalidArgumentException("API key must be set.");
        }

        $response = $this->repository->findByOriginalUrl($url);
        if (empty($response)) {
            $response = $this->checkUrl($url);
            
            if (!empty($response['matches'][0]['cacheDuration'])) {
                $this->repository->setTtl($response['matches'][0]['cacheDuration']);
            }
            $this->repository->save($url, $response);
        }
        return empty($response['matches']);
    }


    private function checkUrl(string $url): array
    {
        $postData = json_encode([
            'client' => $this->client,
            'threatInfo' => [
                'threatTypes' => $this->threatTypes,
                'platformTypes' => $this->platformTypes,
                'threatEntryTypes' => ['URL'],
                'threatEntries' => [
                    ['url' => $url]
                ]
            ]
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiEndpoint . '?key=' . $this->apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }
}
