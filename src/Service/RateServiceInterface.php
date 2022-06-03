<?php

namespace App\Service;

interface RateServiceInterface
{
    public function getCurrencyRateList(): array;
}
