<?php

namespace App\Service;

use App\Entity\Currency;
use App\Entity\CurrencyRate;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImportCurrencyRateService
{
    public function __construct(protected array $currencyRateHandlers, protected HttpClientInterface $httpClient, protected ManagerRegistry $doctrine, protected EntityManagerInterface $em)
    {
    }

    protected function getCurrencyList(): array
    {
        $currencyList = [];
        foreach ($this->doctrine->getRepository(Currency::class)->findAll() as $curItem) {
            /**
             * @var $curItem Currency
             */
            $currencyList[$curItem->getCode()] = $curItem;
        }

        return $currencyList;
    }

    public function getRateList(string $minDate): array
    {
        $rateList = [];
        foreach ($this->em->createQueryBuilder()
                     ->select('c.code, cr.date, cr.value')
                     ->from(CurrencyRate::class, 'cr')
                     ->join('cr.currency', 'c', 'WITH', 'cr.currency=c.id')
                     ->where('cr.date >= :date')
                     ->getQuery()
                     ->setParameter('date', $minDate)
                     ->getResult() as $rateItem) {
            $rateList[$rateItem['code']][$rateItem['date']->format('Y-m-d')] = $rateItem['value'];
        }
        return $rateList;
    }

    public function importCurrencyRate(): void
    {
        $newRateList = [];
        foreach ($this->currencyRateHandlers as $currencyRateHandler) {
            $newRateList = array_merge($newRateList, (new $currencyRateHandler($this->httpClient))->getCurrencyRateList());
        }

        $arCurrency = $this->getCurrencyList();
        $minDate = $this->getMinDate($newRateList);
        $arCurrencyRates = $this->getRateList($minDate);
        foreach ($newRateList as $code => $item) {
            foreach ($item as $date => $rate) {
                if (!isset($arCurrency[$code])) {
                    $arCurrency[$code] = (new Currency())->setCode($code);
                }
                if (!isset($arCurrencyRates[$code][$date])) {
                    $currencyRate = (new CurrencyRate())
                        ->setCurrency($arCurrency[$code])
                        ->setDate(new DateTime($date))
                        ->setValue($rate);
                    $this->doctrine->getManager()->persist($currencyRate);
                }
                $this->doctrine->getManager()->flush();
            }
        }
    }

    private function getMinDate(array $newRateList)
    {
        return date('Y-m-d', array_reduce($newRateList, function ($carry, $item) {
            foreach (array_keys($item) as $date) {
                if (strtotime($date) < $carry) {
                    $carry = strtotime($date);
                }
            }
            return $carry;
        }, getdate()));
    }
}
