<?php

declare(strict_types = 1);

namespace Tests\unit\Statistics\Calculator;

use DateTime;
use SocialPost\Dto\SocialPostTo;
use Statistics\Calculator\AveragePostsPerUserPerMonth;
use Statistics\Dto\ParamsTo;
use Statistics\Dto\StatisticsTo;
use Statistics\Enum\StatsEnum;

class AveragePostsPerUserPerMonthTest extends CalculatorBaseTestCase
{
    /**
     * @dataProvider provider
     *
     * @param SocialPostTo[] $posts
     * @param StatisticsTo[] $expectedStatistics
     */
    public function testShouldCalculateStatistics(
        ParamsTo $paramsTo,
        array $posts,
        array $expectedStatistics,
    ): void
    {
        $parameters = $this->configureParameters(
            StatsEnum::AVERAGE_POSTS_NUMBER_PER_USER_PER_MONTH,
            new DateTime("2023-01-01"),
            new DateTime("2023-12-31")
        );

        $calculator = new AveragePostsPerUserPerMonth();
        $calculator->setParameters($parameters);

        foreach ($posts as $post) {
            $calculator->accumulateData($post);
        }

        $statistics = $calculator->calculate();

        $this->assertEquals($expectedStatistics, $statistics->getChildren());
    }

    private function provider(): array
    {
        return [
            [
                $this->configureParameters(
                    StatsEnum::AVERAGE_POSTS_NUMBER_PER_USER_PER_MONTH,
                    new DateTime("2023-01-01"),
                    new DateTime("2023-12-31")
                ),
                [], // total posts
                [], // expected StatisticsTo[]
            ],
            [
                $this->configureParameters(
                    StatsEnum::AVERAGE_POSTS_NUMBER_PER_USER_PER_MONTH,
                    new DateTime("2023-01-01"),
                    new DateTime("2023-12-31")
                ),
                [
                    $this->configurePostFor("user_1", new DateTime("2023-01-01")),
                    $this->configurePostFor("user_2", new DateTime("2023-01-01")),
                ],
                [
                    $this->configureStatistics(
                        StatsEnum::AVERAGE_POSTS_NUMBER_PER_USER_PER_MONTH,
                        "January, 2023",
                        "posts",
                        1.0
                    )
                ]
            ],
            [
                $this->configureParameters(
                    StatsEnum::AVERAGE_POSTS_NUMBER_PER_USER_PER_MONTH,
                    new DateTime("2023-01-01"),
                    new DateTime("2023-12-31")
                ),
                [
                    $this->configurePostFor("user_1", new DateTime("2023-01-01")),
                    $this->configurePostFor("user_1", new DateTime("2023-02-01")),
                    $this->configurePostFor("user_2", new DateTime("2023-01-01")),
                    $this->configurePostFor("user_2", new DateTime("2023-01-01")),
                    $this->configurePostFor("user_3", new DateTime("2023-01-01")),
                    $this->configurePostFor("user_3", new DateTime("2023-02-01")),
                ],
                [
                    $this->configureStatistics(
                        StatsEnum::AVERAGE_POSTS_NUMBER_PER_USER_PER_MONTH,
                        "January, 2023",
                        "posts",
                        1.3333333333333333
                    ),
                    $this->configureStatistics(
                        StatsEnum::AVERAGE_POSTS_NUMBER_PER_USER_PER_MONTH,
                        "February, 2023",
                        "posts",
                        1.0
                    )
                ]
            ]
        ];
    }
}
