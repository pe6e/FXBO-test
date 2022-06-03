<?php

namespace App\Command;

use App\Entity\Currency;
use App\Entity\CurrencyRate;
use App\Service\ImportCurrencyRateService;
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

    public function __construct(protected HttpClientInterface $client, protected ManagerRegistry $doctrine, string $name = null, protected ImportCurrencyRateService $importCurrencyRateService)
    {
        parent::__construct($name);
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->importCurrencyRateService->importCurrencyRate();
            $output->write('Курсы валют импортированы в базу' . PHP_EOL);
            return self::SUCCESS;
        } catch (\Exception|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            $output->write($e->getMessage());
            return self::FAILURE;
        }
    }
}
