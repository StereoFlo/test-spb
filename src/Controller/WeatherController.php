<?php

namespace Controller;

use Carbon\Carbon;
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
    public function getPeriod(): Response
    {
        $year     = $this->request->get('year', Carbon::now()->year);
        $month    = $this->request->get('month', Carbon::now()->month);
        $startDay = $this->request->get('startDay', Carbon::now()->startOfWeek(Carbon::MONDAY)->day);
        $endDay   = $this->request->get('endDay', Carbon::now()->endOfWeek(Carbon::SUNDAY)->day);

        $week = $this->weatherModel->getPeriod($year, $month, $startDay, $endDay);
        return $this->render('Weather/week.html.twig', ['week' => $week]);
    }
}