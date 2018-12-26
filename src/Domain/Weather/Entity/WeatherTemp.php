<?php


namespace Domain\Weather\Entity;

/**
 * Class WeatherTemp
 * @package Domain\Weather\Entity
 */
class WeatherTemp
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $weatherId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $tempFrom;

    /**
     * @var string
     */
    private $tempTo;

    /**
     * @var Weather|null
     */
    private $weather;

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
     * @return WeatherTemp
     */
    public function setId(int $id): WeatherTemp
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return WeatherTemp
     */
    public function setName(string $name): WeatherTemp
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getTempFrom(): string
    {
        return $this->tempFrom;
    }

    /**
     * @param string $tempFrom
     *
     * @return WeatherTemp
     */
    public function setTempFrom(string $tempFrom): WeatherTemp
    {
        $this->tempFrom = $tempFrom;
        return $this;
    }

    /**
     * @return string
     */
    public function getTempTo(): string
    {
        return $this->tempTo;
    }

    /**
     * @param string $tempTo
     *
     * @return WeatherTemp
     */
    public function setTempTo(string $tempTo): WeatherTemp
    {
        $this->tempTo = $tempTo;
        return $this;
    }

    /**
     * @return int
     */
    public function getWeatherId(): int
    {
        return $this->weatherId;
    }

    /**
     * @param int $weatherId
     *
     * @return WeatherTemp
     */
    public function setWeatherId(int $weatherId): WeatherTemp
    {
        $this->weatherId = $weatherId;
        return $this;
    }

    /**
     * @return Weather|null
     */
    public function getWeather(): ?Weather
    {
        return $this->weather;
    }

    /**
     * @param Weather|null $weather
     *
     * @return WeatherTemp
     */
    public function setWeather(?Weather $weather): WeatherTemp
    {
        $this->weather = $weather;

        return $this;
    }
}