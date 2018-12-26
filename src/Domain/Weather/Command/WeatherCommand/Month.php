<?php

namespace Domain\Weather\Command\WeatherCommand;

/**
 * Class Month
 * @package Domain\Weather\Command\WeatherCommand
 */
class Month extends AbstractItem
{

    /**
     * @var Day[]
     */
    private $days;

    /**
     * @var int
     */
    private $count = 0;

    /**
     * Month constructor.
     *
     * @param int $month
     * @param Day $day
     */
    public function __construct(int $month, Day $day)
    {
        $this->number = $month;
        $this->days[] = $day;
        $this->count += 1;
    }

    /**
     * @param int $month
     * @param Day $day
     *
     * @return Month
     */
    public static function create(int $month, Day $day): self
    {
        return new self($month, $day);
    }

    /**
     * @return Day[]
     */
    public function getDays(): array
    {
        return $this->days;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }
}