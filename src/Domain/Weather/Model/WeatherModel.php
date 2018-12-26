<?php

namespace Domain\Weather\Model;

use Carbon\Carbon;
use Domain\Weather\Entity\Weather;
use Domain\Weather\Repository\WeatherRepository;

/**
 * Class WeatherModel
 * @package Domain\Weather\Model
 */
class WeatherModel
{
    /**
     * @var WeatherRepository
     */
    private $weatherRepo;

    /**
     * WeatherModel constructor.
     *
     * @param WeatherRepository $weatherRepo
     */
    public function __construct(WeatherRepository $weatherRepo)
    {
        $this->weatherRepo = $weatherRepo;
    }

    /**
     * @return Weather|null
     */
    public function getToday(): ?Weather
    {
        return $this->weatherRepo->getByDate(Carbon::now()->year, Carbon::now()->month, Carbon::now()->day);
    }

    /**
     * @return Weather[]|null
     */
    public function getWeek(): ?array
    {
        return $this->weatherRepo->getWeek(Carbon::now()->year, Carbon::now()->month, Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());
    }
}