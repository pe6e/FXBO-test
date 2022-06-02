<?php

namespace App\Service;

use Symfony\Component\DomCrawler\Crawler;

class EcbService extends RateService
{
    protected const SOURCE_URL = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';

    public function getCurrencyRateList(): array
    {
        $data = $this->getUrlData($this->getSourceUrl());
        $crawler = new Crawler($data);
        $date = $crawler->filter('[time]')->extract(['time'])[0];
        $arrCurrencyRate = [];
        foreach ($crawler->filter('[currency]')->extract(['currency', 'rate']) as $item) {
            $arrCurrencyRate[$item[0]][$date] = $item[1];
        }

        return $arrCurrencyRate;
    }

    protected function getSourceUrl(): string
    {
        return self::SOURCE_URL;
    }
}