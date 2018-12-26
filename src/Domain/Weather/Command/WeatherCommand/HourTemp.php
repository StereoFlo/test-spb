<?php

namespace Domain\Weather\Command\WeatherCommand;

/**
 * Class HourTemp
 * @package Domain\Weather\Command\WeatherCommand
 */
class HourTemp
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $tempFrom;

    /**
     * @var null|string
     */
    private $tempTo;

    /**
     * HourTemp constructor.
     *
     * @param string      $name
     * @param string      $tempFrom
     * @param null|string $tempTo
     */
    public function __construct(string $name, string $tempFrom, ?string $tempTo)
    {
        $this->name = $name;
        $this->tempFrom = $tempFrom;
        $this->tempTo = $tempTo;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getTempFrom(): string
    {
        return $this->tempFrom;
    }

    /**
     * @return null|string
     */
    public function getTempTo(): ?string
    {
        return $this->tempTo;
    }
}