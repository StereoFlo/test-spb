<?php

namespace Tests;

use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Domain\Weather\Entity\Weather;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class WeatherRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * today's test
     */
    public function testToday(): void
    {
        $today = $this->em->getRepository(Weather::class)->findOneBy(['year' => Carbon::now()->year, 'month' => Carbon::now()->month, 'day' => Carbon::now()->day]);
        $this->assertTrue(!is_null($today));
        $this->assertTrue($today->getYear() === (string) Carbon::now()->year);
        $this->assertTrue($today->getMonth() === (string) Carbon::now()->month);
        $this->assertTrue($today->getDay() === (string) Carbon::now()->day);
        $this->assertTrue(!$today->getWeatherTemp()->isEmpty());
    }


    /**
     * setup method
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();
        $this->em = $kernel->getContainer()->get('doctrine')->getManager();
    }

}