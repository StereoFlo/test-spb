<?php


namespace Domain\Weather\Repository;

use Domain\Shared\Repository\AbstractRepository;
use Domain\Weather\Entity\Weather;

/**
 * Class WeatherRepository
 * @package Domain\Weather\Repository
 */
class WeatherRepository extends AbstractRepository
{
    /**
     * @param Weather $weather
     *
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Weather $weather): bool
    {
        return parent::saveOne($weather);
    }

    /**
     * @param Weather[] $objects
     *
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveMany(array $objects): bool
    {
        return parent::saveMany($objects);
    }

    /**
     * @param string $year
     * @param string $month
     * @param string $day
     *
     * @return Weather|null|object
     */
    public function getByDate(string $year, string $month, string $day)
    {
        return $this->manager->getRepository(Weather::class)->findOneBy(['year' => $year, 'month' => $month, 'day' => $day]);
    }

    /**
     * @param string $year
     * @param string $month
     * @param string $weekStart
     * @param string $weekEnd
     *
     * @return Weather[]|null
     */
    public function getWeek(string $year, string $month, string $weekStart, string $weekEnd): ?array
    {
        return $this->manager
            ->createQueryBuilder()
            ->from(Weather::class, 'weather')
            ->where('weather.year = :year')
            ->andWhere('weather.month = :month')
            ->andWhere('weather.day >= :weekStart and weather.day <= :weekEnd')
            ->setParameter('weekEnd', $weekEnd)
            ->setParameter('weekStart', $weekStart)
            ->setParameter('year', $year)
            ->setParameter('month', $month)
            ->getQuery()
            ->getResult();
    }
}