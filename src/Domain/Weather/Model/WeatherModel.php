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
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->day;
        $weekEnd   = Carbon::now()->endOfWeek(Carbon::SUNDAY)->day;

        return $this->weatherRepo->getWeek($year, $month, $weekStart, $weekEnd);
    }
}