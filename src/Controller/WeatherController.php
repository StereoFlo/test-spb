<?php

namespace Controller;

use Domain\Weather\Model\WeatherModel;

/**
 * Class WeatherController
 * @package Controller
 */
class WeatherController extends AbstractController
{
    /**
     * @var WeatherModel
     */
    private $weatherModel;

    /**
     * WeatherController constructor.
     *
     * @param WeatherModel $weatherModel
     */
    public function __construct(WeatherModel $weatherModel)
    {
        $this->weatherModel = $weatherModel;
    }

    public function getDay()
    {
        $today = $this->weatherModel->getToday();
        return $this->render('Weather/today.html.twig', ['today' => $today]);
    }

    public function getWeek()
    {
        $week = $this->weatherModel->getWeek();
        return $this->render('Weather/week.html.twig', ['week' => $week]);
    }
}