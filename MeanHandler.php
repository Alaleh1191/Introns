<?php

class MeanHandler implements FileHandler
{
    /**
     * @var IntervalsHandler $intervals
     */
    protected $intervals;

    public function __construct(IntervalsHandler $intervals)
    {
        $this->intervals = $intervals;
    }


    /**
     * @param $array array
     * Array to be processed
     *
     * @return void
     */
    public function process($array)
    {
        if($array[BedFile::$chromosomeCol] == 'all')
        {
            return;
        }

        // Extract Interval
        $key = $array[BedFile::$startIntervalCol].'_'.$array[BedFile::$endIntervalCol];
        /** @var Interval $interval */
        $interval = $this->intervals->getInterval($key);

        // Extract values from line
        $depth = $array[BedFile::$depthCol];
        $basesAtDepth = $array[BedFile::$basesCol];
        $intervalLength = $array[BedFile::$intervalLengthCol];

        // Calculate the mean for the interval
        $interval->calculateMean($depth, $basesAtDepth, $intervalLength);
    }

    /**
     * @param $line string
     * Turn current lines into an array
     *
     * @return array
     */
    public function processLine($line)
    {
        return str_getcsv($line, "\t");
    }
}