<?php

namespace App\Command;

use App\Entity\Currency;
use App\Entity\CurrencyRate;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(name: 'app:import-currency-rate')]
class ImportCurrencyRate extends Command
{
    protected static $defaultName = 'app:import-currency-rate';

    protected static $defaultDescription = 'Получение курсов валют с ecb.europa.eu и coindesk.com';

    protected string $urlECB = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';

    protected string $urlCoindesk = 'https://api.coindesk.com/v1/bpi/historical/close.json';

    public function __construct(protected HttpClientInterface $client, protected ManagerRegistry $doctrine, string $name = null)
    {
        parent::__construct($name);
    }


    protected function getUrlData(string $url): string
    {
        try {
//            dd(get_class($this->client));
//            if ($url !== 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml')
//            dd($this->client->request(
//                'GET',
//                $url,
//                ['headers' => [
//                'Accept' => 'application/json',
//            ]]
//            )->getContent());
            return $this->client->request(
                'GET',
                $url
            )->getContent();
        } catch (ClientExceptionInterface $e) {
            dd(1);
        } catch (RedirectionExceptionInterface $e) {
            dd(2);
        } catch (ServerExceptionInterface $e) {
            dd(3);
        } catch (TransportExceptionInterface $e) {
            dd(4);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {

            $data1 = $this->getUrlData('https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml');
            //если ошибка - возвращаем ошибку "не удалось получить данные из источника 1"
            //return Command::FAILURE;
            $crawler = new Crawler($data1);
            $arrCurrencyRate = [];
            $date = $crawler->filter('[time]')->extract(['time'])[0];
            foreach ($crawler->filter('[currency]')->extract(['currency', 'rate']) as $item) {
                $arrCurrencyRate[$item[0]][$date] = $item[1];
            }
            //если не получилось - возвращаем ошибку "некорректный формат"
            //return Command::FAILURE;
            $data2 = json_decode($this->getUrlData('https://api.coindesk.com/v1/bpi/historical/close.json'));
            //если не получилось - возвращаем ошибку "некорректные данные"
            foreach ($data2->bpi as $k => $v) {
                $arrCurrencyRate['BPI'][$k] = (string)$v;
            }

            $arCurrency = [];
            foreach ($arrCurrencyRate as $code => $item) {
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
//        print_r($arrCurrencyRate);
        dd($this->doctrine->getManager()->flush());
            //если ошибка - возвращаем ошибку "не удалось получить данные из источника 2"
            //return Command::FAILURE;
//        $currencyCodeArr = array_keys($arrCurrencyRate);
//        print_r($currencyCodeArr);
//        $currencyArrBase = [];
            //return Command::FAILURE;

            //код выше вынести в метод и дальше рефакторить
            //готовим данные к занесению
            //получаем массив валют по кодам из базы
//        foreach ($this->doctrine->getRepository(Currency::class)->findBy(['code' => array_keys($arrCurrencyRate)]) as $item) {
//            $currencyArrBase[$item->getCode()] = $item;
//        }

            //если появилась новая валюта - заносим её
//        $flush = false;
//        foreach (array_keys($arrCurrencyRate) as $code) {
//            if (!isset($currencyArrBase[$code])) {
//                $currency = (new Currency())
//                    ->setCode($code);
//                $this->doctrine->getManager()->persist($currency);
//                $currencyArrBase[$code] = $currency
//                $flush = true;
//            }
//        }

//        if ($flush) {
//            $this->doctrine->getManager()->flush();
//        }

//        print_r($arrCurrencyRate);
            //готовим массив с курсами валют
            //заносим их (с заменой) - уник это дата + валюта
//        foreach ($arrCurrencyRate as $code => &$item) {
//            $item->setCurrency()
//
//        }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            dd($e->getMessage());
            return Command::FAILURE;
        }
    }
}
