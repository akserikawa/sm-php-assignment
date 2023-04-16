<?php

declare(strict_types=1);

namespace Statistics\Calculator;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;

class AveragePostsPerUserPerMonth extends AbstractCalculator
{
    protected const UNITS = 'posts';

    /** @var array<string,array<string,mixed>> */
    private array $totals = [];

    protected function doAccumulate(SocialPostTo $postTo): void
    {
        $month = $postTo->getDate()->format('F, Y');
        $this->add($postTo, $month);
    }

    private function add(SocialPostTo $postTo, string $month): void
    {
        $this->initializeMonth($month);

        $this->totals[$month]['posts'] += 1;
        $this->totals[$month]['users'][] = $postTo->getAuthorId();
    }

    private function initializeMonth(string $month): void
    {
        if (!isset($this->totals[$month])) {
            $this->totals[$month] = [
                'posts' => 0,
                'users' => []
            ];
        }
    }

    protected function doCalculate(): StatisticsTo
    {
        $stats = new StatisticsTo();
        foreach ($this->totals as $month => $monthlyTotals) {
            $uniqueUsersPerMonth = count(array_unique($monthlyTotals['users']));

            $child = (new StatisticsTo())
                ->setName($this->parameters->getStatName())
                ->setSplitPeriod($month)
                ->setValue($monthlyTotals['posts'] / $uniqueUsersPerMonth)
                ->setUnits(self::UNITS);

            $stats->addChild($child);
        }

        return $stats;
    }
}
