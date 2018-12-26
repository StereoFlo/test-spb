<?php

namespace Domain\Weather\Repository;

use Domain\Shared\Repository\AbstractRepository;
use Domain\Weather\Entity\WeatherTemp;

/**
 * Class WeatherTempRepository
 * @package Domain\Weather\Repository
 */
class WeatherTempRepository extends AbstractRepository
{
    /**
     * @param WeatherTemp $temp
     *
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(WeatherTemp $temp): bool
    {
        return parent::saveOne($temp);
    }

    /**
     * @param array $temp
     *
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveMany(array $temp): bool
    {
        return parent::saveMany($temp);
    }
}