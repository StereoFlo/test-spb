<?php

namespace Domain\Weather\Command;

use Domain\Weather\Command\WeatherCommand\Day;
use Domain\Weather\Command\WeatherCommand\HourTemp;
use Domain\Weather\Command\WeatherCommand\Month;
use Domain\Weather\Command\WeatherCommand\Year;
use Domain\Weather\Entity\Weather;
use Domain\Weather\Entity\WeatherTemp;
use Domain\Weather\Repository\WeatherRepository;
use Domain\Weather\Repository\WeatherTempRepository;
use Infrastructure\Helpers\MonthNormalizer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class WeatherCommand
 * @package Domain\Weather\Command
 */
class WeatherCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'weather:update';

    /**
     * @var string
     */
    private $currentMonth;

    /**
     * @var string
     */
    private $currentDay;

    /**
     * @var string
     */
    private $currentYear;

    /**
     * @var string
     */
    private $currentMaxYear;

    /**
     * @var WeatherRepository
     */
    private $weatherRepository;
    /**
     * @var WeatherTempRepository
     */
    private $weatherTempRepository;

    /**
     * WeatherCommand constructor.
     *
     * @param WeatherRepository     $weatherRepository
     * @param WeatherTempRepository $weatherTempRepository
     */
    public function __construct(WeatherRepository $weatherRepository, WeatherTempRepository $weatherTempRepository)
    {
        parent::__construct();
        $this->weatherRepository = $weatherRepository;
        $this->weatherTempRepository = $weatherTempRepository;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * execute
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $crawler = new Crawler(file_get_contents('https://yandex.ru/pogoda/moscow/details'));
        $days = $this->getRawDays($crawler);
        $res = [];
        foreach ($days as $day) {
            $this->initVars($day);
            $res[] = $this->getResult($day);
        }
        /** @var Year $weather */
        foreach ($res as $weather) {
            $year = $weather->getNumber();
            $currentMonth = null;
            $currentDay = null;
            foreach ($weather->getMonth() as $month) {
                $currentMonth = $month->getNumber();
                foreach ($month->getDays() as $day) {
                    $currentDay = $day->getNumber();
                    $weather = $this->weatherRepository->getByDate($year, $currentMonth, $currentDay);
                    if (empty($weather)) {
                        $weather = new Weather();
                        $weather->setMonth($currentMonth);
                        $weather->setDay($currentDay);
                        $weather->setYear($year);
                        $this->weatherRepository->save($weather);

                        $temp = [];
                        foreach ($day->getHourTemp() as $hourTemp) {
                                $temp[] = (new WeatherTemp())
                                    ->setWeather($weather)
                                    ->setWeatherId($weather->getId())
                                    ->setName($hourTemp->getName())
                                    ->setTempFrom($hourTemp->getTempFrom())
                                    ->setTempTo($hourTemp->getTempTo());
                        }
                        $this->weatherTempRepository->saveMany($temp);
                    }
                }
            }
        }
    }

    /**
     * @param Crawler $crawler
     *
     * @return array
     */
    protected function getRawDays(Crawler $crawler): array
    {
        $tempTable = $crawler->filter('.forecast-details');
        $attrs = [];
        foreach ($tempTable->children() as $item) {
            $className = $item->getAttribute('class');
            //todo это надо сделать красиво
            if ($className !== 'forecast-details__day') {
                if ($className !== 'forecast-details__day-info') {
                    if ($className !== 'forecast-details__day forecast-details__day_weekend') {
                        continue;
                    }
                }
            }

            $attrs[] = $item;
        }
        return \array_chunk($attrs, 2);
    }

    /**
     * @param array $day
     *
     * @return void
     */
    protected function initVars(array $day): void
    {
        if (isset($day[0]) && $day[0]->nodeName === 'dt') {
            $dayVal = $day[0]->childNodes->item(0)->nodeValue;
            $monthVal = $day[0]->childNodes->item(1)->childNodes->item(0)->nodeValue;
            $monthValNorm = MonthNormalizer::create()->setMonth($monthVal)->getMonthNumber();
            $this->currentDay = $dayVal;
            if ($this->currentYear && $monthValNorm < $this->currentMonth) {
                $this->currentMaxYear = \date('Y') + 1;
            } else {
                $this->currentYear = $this->currentMaxYear ? $this->currentMaxYear : \date('Y');
            }
            $this->currentMonth = $monthValNorm;
        }
    }

    /**
     * @param array $day
     *
     * @return Year
     */
    protected function getResult(array $day): Year
    {
        $month = [];
        if (isset($day[1]) && $day[1]->nodeName === 'dd') {
            $table = $day[1]->childNodes->item(0);
            $tbody = $table->childNodes->item(1);
            $hourTemp = [];
            foreach ($tbody->childNodes as $tr) {
                $dayPart = $tr->childNodes->item(0)->childNodes->item(0)->childNodes->item(0)->nodeValue;
                $tempFrom = $tr->childNodes->item(0)->childNodes->item(0)->childNodes->item(1)->childNodes->item(0)->nodeValue;
                $tempTo = null;
                $tempTo = $this->getTempTo($tr);

                $hourTemp[] = new HourTemp($dayPart, str_replace('°', '', $tempFrom), str_replace('°', '', $tempTo));
            }
            $month[] = Month::create($this->currentMonth, new Day($this->currentDay, $hourTemp));
        }
        return new Year($this->currentYear, $month);
    }

    /**
     * @param $tr
     *
     * @return string|null
     */
    protected function getTempTo($tr): ?string
    {
        if ($tr->childNodes->item(0)->childNodes->item(0)->childNodes->item(1)->childNodes->item(2)) {
            return $tr->childNodes->item(0)->childNodes->item(0)->childNodes->item(1)->childNodes->item(2)->nodeValue;
        }
        if (!$tr->childNodes->item(0)->childNodes->item(0)->childNodes->item(1)->childNodes->item(1)) {
            return $tr->childNodes->item(0)->childNodes->item(0)->childNodes->item(1)->childNodes->item(1)->nodeValue;
        }
        return null;
    }
}