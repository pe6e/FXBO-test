<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class RateService implements RateServiceInterface
{
    public function __construct(protected HttpClientInterface $httpClient)
    {
    }

    protected function getUrlData(string $url): string
    {
        return $this->httpClient->request(
            'GET',
            $url
        )->getContent();
    }

    abstract public function getCurrencyRateList(): array;
}
