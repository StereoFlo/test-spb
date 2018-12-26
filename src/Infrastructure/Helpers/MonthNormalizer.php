<?php

namespace Infrastructure\Helpers;

/**
 * Class MonthNormalizer
 * @package Infrastructure\Helpers
 */
class MonthNormalizer
{
    const MONTH_NAMES = [
        1  => ['январь', 'января'],
        2  => ['февраль', 'фервраля'],
        3  => ['март', 'марта'],
        4  => ['апрель', 'апреля'],
        5  => ['май', 'мая'],
        6  => ['июнь', 'июня'],
        7  => ['июль', 'июля'],
        8  => ['август', 'августа'],
        9  => ['сентябрь', 'сентября'],
        10 => ['октябрь', 'октября'],
        11 => ['ноябрь', 'ноября'],
        12 => ['декабрь', 'декабря'],
    ];

    /**
     * @var string
     */
    private $month;

    /**
     * @return MonthNormalizer
     */
    public static function create(): self
    {
        return new self();
    }

    /**
     * @param string $month
     *
     * @return MonthNormalizer
     */
    public function setMonth(string $month): MonthNormalizer
    {
        $this->month = $month;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMonthNumber(): ?int
    {
        foreach (self::MONTH_NAMES as $monthNumber => $month) {
            if (\in_array($this->month, $month)) {
                return $monthNumber;
            }
        }
        return null;
    }
}