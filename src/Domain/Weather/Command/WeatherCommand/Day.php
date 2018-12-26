<?php

namespace Domain\Weather\Command\WeatherCommand;

/**
 * Class Day
 * @package Domain\Weather\Command\WeatherCommand
 */
class Day extends AbstractItem
{
    /**
     * @var HourTemp[]
     */
    private $hourTemp;

    /**
     * Day constructor.
     *
     * @param int   $number
     * @param array $hourTemp
     */
    public function __construct(int $number, array $hourTemp)
    {
        $this->number = $number;
        $this->hourTemp = $hourTemp;
    }

    /**
     * @return HourTemp[]
     */
    public function getHourTemp(): array
    {
        return $this->hourTemp;
    }
}