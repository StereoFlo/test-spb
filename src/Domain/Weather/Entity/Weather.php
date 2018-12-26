<?php

namespace Domain\Weather\Entity;

use Doctrine\ORM\PersistentCollection;

/**
 * Class Weather
 * @package Domain\Weather\Entity
 */
class Weather
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $year;

    /**
     * @var string
     */
    private $month;

    /**
     * @var string
     */
    private $day;

    /**
     * @var WeatherTemp[]|null|PersistentCollection
     */
    private $weatherTemp;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Weather
     */
    public function setId(int $id): Weather
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getYear(): string
    {
        return $this->year;
    }

    /**
     * @param string $year
     *
     * @return Weather
     */
    public function setYear(string $year): Weather
    {
        $this->year = $year;
        return $this;
    }

    /**
     * @return string
     */
    public function getMonth(): string
    {
        return $this->month;
    }

    /**
     * @param string $month
     *
     * @return Weather
     */
    public function setMonth(string $month): Weather
    {
        $this->month = $month;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param mixed $day
     *
     * @return Weather
     */
    public function setDay($day): Weather
    {
        $this->day = $day;
        return $this;
    }

    /**
     * @return PersistentCollection|WeatherTemp[]|null
     */
    public function getWeatherTemp()
    {
        return $this->weatherTemp;
    }
}