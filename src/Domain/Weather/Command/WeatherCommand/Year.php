<?php

namespace Domain\Weather\Command\WeatherCommand;

/**
 * Class Year
 * @package Domain\Weather\Command\WeatherCommand
 */
class Year extends AbstractItem
{
    /**
     * @var Month[]
     */
    private $month;

    /**
     * Year constructor.
     *
     * @param int   $number
     * @param Month[] $month
     */
    public function __construct(int $number, array $month)
    {
        $this->number = $number;
        $this->month  = $month;
    }

    /**
     * @return Month[]
     */
    public function getMonth(): array
    {
        return $this->month;
    }
}