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
     * @param string $year
     * @param string $month
     * @param string $startDay
     * @param string $entDay
     *
     * @return array|null
     */
    public function getPeriod(string $year, string $month, string $startDay, string $entDay): ?array
    {
        return $this->weatherRepo->getWeek($year, $month, $startDay, $entDay);
    }
}