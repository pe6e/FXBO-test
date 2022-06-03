<?php

namespace App\Service;

use Symfony\Component\DomCrawler\Crawler;

class CoindeskService extends RateService
{
    protected const SOURCE_URL = 'https://api.coindesk.com/v1/bpi/historical/close.json';

    public function getCurrencyRateList(): array
    {
        $arrCurrencyRate = [];
//        $data = json_decode($this->getUrlData('https://api.coindesk.com/v1/bpi/historical/close.json'));
        $data = json_decode('{"bpi":{"2022-05-02":38913.875,"2022-05-03":38580.5,"2022-05-04":38368.5,"2022-05-05":39690.5,"2022-05-06":36469.28,"2022-05-07":35933.5,"2022-05-08":34637.3075,"2022-05-09":33528.5,"2022-05-10":32050.5,"2022-05-11":31474.35,"2022-05-12":27127.9625,"2022-05-13":30219.84,"2022-05-14":29378.75,"2022-05-15":29817.5,"2022-05-16":30376.445,"2022-05-17":30160.5,"2022-05-22":29339.5,"2022-05-23":30133.5,"2022-05-26":29085.5,"2022-05-27":28870.625,"2022-05-28":28769.675,"2022-05-29":29181.0225,"2022-05-30":30592.5,"2022-05-31":31533.775,"2022-06-01":31451.5},"disclaimer":"This data was produced from the CoinDesk Bitcoin Price Index. BPI value data returned as USD.","time":{"updated":"Jun 2, 2022 00:03:00 UTC","updatedISO":"2022-06-02T00:03:00+00:00"}}');
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
