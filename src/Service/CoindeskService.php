<?php

namespace App\Service;

use Symfony\Component\DomCrawler\Crawler;

class CoindeskService extends RateService
{

    protected const SOURCE_URL = 'https://api.coindesk.com/v1/bpi/historical/close.json';

    public function getCurrencyRateList(): array
    {
        $arrCurrencyRate = [];
        $data = json_decode($this->getUrlData('https://api.coindesk.com/v1/bpi/historical/close.json'));

        foreach ($data->bpi as $k => $v) {
            $arrCurrencyRate['BPI'][$k] = (string)$v;
        }

        return $arrCurrencyRate;
    }

    protected function getSourceUrl(): string
    {
        return self::SOURCE_URL;
    }
}