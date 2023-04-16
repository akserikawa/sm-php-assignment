<?php

declare(strict_types=1);

namespace Tests\unit\Statistics\Calculator;

use DateTime;
use PHPUnit\Framework\TestCase;
use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\ParamsTo;
use Statistics\Dto\StatisticsTo;

class CalculatorBaseTestCase extends TestCase
{
    protected function configureParameters(string $statName, DateTime $startDate, DateTime $endDate): ParamsTo
    {
        return (new ParamsTo())
            ->setStatName($statName)
            ->setStartDate($startDate)
            ->setEndDate($endDate);
    }

    /**
     * @throws \Exception
     */
    protected function configurePostFor(string $authorId, DateTime $date): SocialPostTo
    {
        return (new SocialPostTo())
            ->setAuthorId($authorId)
            ->setType("status")
            ->setDate($date);
    }

    protected function configureStatistics(string $name, string $splitPeriod, string $unit, float $value): StatisticsTo
    {
        return (new StatisticsTo())
            ->setName($name)
            ->setSplitPeriod($splitPeriod)
            ->setUnits($unit)
            ->setValue($value);
    }
}
