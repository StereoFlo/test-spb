<?php

namespace Tests;

use Infrastructure\Helpers\MonthNormalizer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MonthNormalizerTest extends KernelTestCase
{
    public function testMonth()
    {
        $number = MonthNormalizer::create()->setMonth('январь')->getMonthNumber();
        $this->assertEquals(1, $number);
        $number = MonthNormalizer::create()->setMonth('декабрь')->getMonthNumber();
        $this->assertEquals(12, $number);
    }
}