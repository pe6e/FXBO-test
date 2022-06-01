<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:import-currency-rate')]
class ImportCurrencyRate extends Command
{
    protected static $defaultName = 'app:import-currency-rate';

    protected static $defaultDescription = 'Получение курсов валют с ecb.europa.eu и coindesk.com';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //получаем данные из источника 1
            //если ошибка - возвращаем ошибку "не удалось получить данные из источника 1"
            //return Command::FAILURE;
        //парсим результаты 1 (xml)
            //если не получилось - возвращаем ошибку "некорректный формат"
            //return Command::FAILURE;
        //получаем данные из источника 2
            //если ошибка - возвращаем ошибку "не удалось получить данные из источника 2"
            //return Command::FAILURE;
        //парсим результаты 2 (json)
            //если не получилось - возвращаем ошибку "некорректные данные"
            //return Command::FAILURE;
            //код выше вынести в метод и дальше рефакторить
        //готовим данные к занесению
            //получаем массив валют по кодам из базы
            //если появилась новая валюта - заносим её
            //готовим массив с курсами валют
        //заносим их (с заменой) - уник это дата + валюта
        return Command::SUCCESS;
    }
}