<?php

namespace Infrastructure\Parser;

use Domain\Weather\Command\WeatherCommand\Day;
use Domain\Weather\Command\WeatherCommand\HourTemp;
use Domain\Weather\Command\WeatherCommand\Month;
use Domain\Weather\Command\WeatherCommand\Year;
use Infrastructure\Helpers\MonthNormalizer;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class WeatherParser
 * @package Infrastructure\Parser
 */
class WeatherParser
{
    const DAY_DETAILS_CLASS         = 'forecast-details__day',
        DAY_DETAILS_CLASS_WEEKEND = 'forecast-details__day forecast-details__day_weekend',
        DAY_INFO_CLASS            = 'forecast-details__day-info';

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
     * @var string
     */
    private $cityName;

    /**
     * @param string $city
     *
     * @return array
     */
    public function getData(string $city = 'moscow'): array
    {
        $this->cityName = $city;
        $crawler = new Crawler(file_get_contents('https://yandex.ru/pogoda/' . $city . '/details'));
        $days = $this->getRawDays($crawler);
        $res = [];
        foreach ($days as $day) {
            $this->initVars($day);
            $res[] = $this->build($day);
        }

        return $res;
    }

    /**
     * @return string
     */
    public function getCityName(): string
    {
        return $this->cityName;
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
            if ($className !== self::DAY_DETAILS_CLASS) {
                if ($className !== self::DAY_INFO_CLASS) {
                    if ($className !== self::DAY_DETAILS_CLASS_WEEKEND) {
                        continue;
                    }
                }
            }

            $attrs[] = $item;
        }
        return \array_chunk($attrs, 2);
    }

    /**
     * @param \DOMElement[] $day
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
     * @param \DOMElement[] $day
     *
     * @return Year
     */
    protected function build(array $day): Year
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
     * @param \DOMElement $tr
     *
     * @return string|null
     */
    protected function getTempTo(\DOMElement $tr): ?string
    {
        if (!$tr->childNodes->item(0)->childNodes->item(0)->childNodes->item(1)->childNodes->item(2)) {
            return $tr->childNodes->item(0)->childNodes->item(0)->childNodes->item(1)->childNodes->item(1)->nodeValue;
        }
        return $tr->childNodes->item(0)->childNodes->item(0)->childNodes->item(1)->childNodes->item(2)->nodeValue;
    }
}