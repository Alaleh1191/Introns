<?php

class MaxDepthHandler implements FileHandler
{
    protected $intervals;

    public function __construct(IntervalsHandler $intervalsHandler)
    {
        $this->intervals = $intervalsHandler;

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


        $depth = $array[BedFile::$depthCol];

        // Extract Interval
        $key = $array[BedFile::$startIntervalCol].'_'.$array[BedFile::$endIntervalCol];
        /** @var Interval $interval */
        $interval = $this->intervals->getInterval($key);

        $interval->calculateMaxDepth($depth);
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