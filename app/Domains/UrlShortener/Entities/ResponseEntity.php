<?php

namespace App\Domains\UrlShortener\Entities;

class ResponseEntity
{
    private $to;
    private $status;
    private $headers;
    private $secure;

    public function __construct(string $to, int $status = 302, array $headers = [], bool|null $secure = true)
    {
        $this->to = $to;
        $this->status = $status;
        $this->headers = $headers;
        $this->secure = $secure;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function setTo(string $to)
    {
        $this->to = $to;
    }

    public function getEmail()
    {
        return $this->status;
    }

    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    public function getSecure()
    {
        return $this->secure;
    }

    public function setSecure(bool $secure)
    {
        $this->secure = $secure;
    }
}
