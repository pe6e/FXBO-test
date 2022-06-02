<?php

namespace App\Service;

use App\Entity\Currency;
use App\Entity\CurrencyRate;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImportCurrencyRateService
{

    public function __construct(protected HttpClientInterface $httpClient, protected ManagerRegistry $doctrine)
    {
    }

    protected function getCurrencyList(): array
    {
        $currencyList = [];
        foreach ($this->doctrine->getRepository(Currency::class) as $curItem) {
            /**
             * @var $curItem Currency
             */
            $currencyList[$curItem->getCode()] = $curItem;
        }

        return $currencyList;
    }

    public function importCurrencyRate(): void
    {
        $coindesk = new CoindeskService($this->httpClient);
        $ecb = new EcbService($this->httpClient);

        $arCurrency = $this->getCurrencyList();
        foreach (array_merge($coindesk->getCurrencyRateList(), $ecb->getCurrencyRateList()) as $code => $item) {
            foreach ($item as $date => $rate) {
                if (!isset($arCurrency[$code])) {
                    $arCurrency[$code] = (new Currency())->setCode($code);
                }
                $currencyRate = (new CurrencyRate())
                    ->setCurrency($arCurrency[$code])
                    ->setDate(new DateTime($date))
                    ->setValue($rate);
                $this->doctrine->getManager()->persist($currencyRate);
            }
        }
        $this->doctrine->getManager()->flush();
    }
}