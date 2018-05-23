<?php

/**
 * Class EntropyStep2ParsingHandler
 *
 * Read the max depth value for each sub interval
 */
class EntropyStep2ParsingHandler implements FileHandler
{
    /**
     * @var IntervalsHandler $intervalsHandler
     */
    protected $intervalsHandler;

    /** @var int $originalIntervalIndex
     *  keeps track of where we are in the original interval
     */
    protected $originalIntervalIndex;

    public function __construct(IntervalsHandler $intervalsHandler)
    {
        $this->intervalsHandler = $intervalsHandler;
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

        $key = $array[BedFile::$startIntervalCol].'_'.$array[BedFile::$endIntervalCol];

        $interval = $this->intervalsHandler->getSubInterval($key);

        $interval->calculateMaxDepth($array[BedFile::$depthCol]);
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