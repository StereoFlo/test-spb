<?php

namespace Domain\Weather\Command;

use Domain\Weather\Command\WeatherCommand\Year;
use Domain\Weather\Entity\Weather;
use Domain\Weather\Entity\WeatherTemp;
use Domain\Weather\Repository\WeatherRepository;
use Domain\Weather\Repository\WeatherTempRepository;
use Infrastructure\Parser\WeatherParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @var WeatherRepository
     */
    private $weatherRepository;

    /**
     * @var WeatherTempRepository
     */
    private $weatherTempRepository;

    /**
     * @var WeatherParser
     */
    private $weatherParser;

    /**
     * WeatherCommand constructor.
     *
     * @param WeatherRepository     $weatherRepository
     * @param WeatherTempRepository $weatherTempRepository
     * @param WeatherParser         $weatherParser
     */
    public function __construct(WeatherRepository $weatherRepository, WeatherTempRepository $weatherTempRepository, WeatherParser $weatherParser)
    {
        parent::__construct();
        $this->weatherRepository = $weatherRepository;
        $this->weatherTempRepository = $weatherTempRepository;
        $this->weatherParser = $weatherParser;
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
        $res = $this->weatherParser->getData();

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
                        $this->weatherRepository->startTransaction();
                        try {
                            $weather = new Weather();
                            $weather->setMonth($currentMonth);
                            $weather->setDay($currentDay);
                            $weather->setYear($year);
                            $weather->setCityName($this->weatherParser->getCityName());
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
                            $this->weatherRepository->commitTransaction();
                        } catch (\Exception $exception) {
                            $this->weatherRepository->rollbackTransaction();
                            $output->writeln($exception->getMessage());
                        }
                    }
                }
            }
        }
    }
}