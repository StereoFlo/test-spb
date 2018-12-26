<?php

namespace Controller;

use Domain\Weather\Model\WeatherModel;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * @return Response
     */
    public function getDay(): Response
    {
        $today = $this->weatherModel->getToday();
        return $this->render('Weather/today.html.twig', ['today' => $today]);
    }

    /**
     * @return Response
     */
    public function getWeek(): Response
    {
        $week = $this->weatherModel->getWeek();
        return $this->render('Weather/week.html.twig', ['week' => $week]);
    }
}