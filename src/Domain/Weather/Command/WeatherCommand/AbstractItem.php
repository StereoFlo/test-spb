<?php


namespace Domain\Weather\Command\WeatherCommand;

/**
 * Class AbstractItem
 * @package Domain\Weather\Command\WeatherCommand
 */
abstract class AbstractItem
{
    /**
     * @var int
     */
    protected $number;

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }
}