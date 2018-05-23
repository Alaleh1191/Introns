<?php

/**
 * Stores all of the intervals
 *
 * Class Intervals
 */
class IntervalsHandler implements FileHandler
{
    public static $numberOfSubIntervals = 10;

    /** @var Interval[] $intervals
     *  Contains all original intervals
     */
    protected $intervals;


    /** @var Interval[] $subIntervals
     *  Contains all subIntervals
     */
    protected $subIntervals;


    public function __construct()
    {
        $this->intervals = array();
        $this->subIntervals = array();
    }


    /**
     * @param $array array
     * Array to be processed
     *
     * If the interval repeats, that is fine - it will be overwritten
     * but nothing is stored (mean, std) are set to 0 no matter what
     * @return void
     */
    public function process($array)
    {
        if($array[BedFile::$chromosomeCol] == 'all')
        {
            return;
        }

        $key = $array[BedFile::$startIntervalCol].'_'.$array[BedFile::$endIntervalCol];

        $this->intervals[$key] = new Interval($array[BedFile::$startIntervalCol], $array[BedFile::$endIntervalCol]);
        $this->intervals[$key]->chromosome = $array[BedFile::$chromosomeCol];
        $this->intervals[$key]->gene = $array[BedFile::$geneCol];

        $this->createSubIntervals($this->intervals[$key]);
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

    /**
     * @return Interval[]
     */
    public function getIntervalsArray()
    {
        return $this->intervals;
    }

    /**
     * @return Interval[]
     */
    public function getSubIntervalsArray()
    {
        return $this->subIntervals;
    }

    public function getInterval($key)
    {
        return $this->intervals[$key];
    }

    public function getSubInterval($key)
    {
        return $this->subIntervals[$key];
    }

    public function createSubIntervals(Interval $parentInterval)
    {
        // Extract Information from Main Interval
        $chromosome     = $parentInterval->chromosome;
        $startInterval  = $parentInterval->getStart();
        $endInterval    = $parentInterval->getEnd();
        $gene           = $parentInterval->gene;
        $intervalLength = $parentInterval->getLength();

        $smallIntervalLength = floor($intervalLength / IntervalsHandler::$numberOfSubIntervals);

        for($i = 0; $i < IntervalsHandler::$numberOfSubIntervals; $i++)
        {
            $smallIntervalStart = $startInterval + $i * $smallIntervalLength;
            $smallIntervalEnd   = $startInterval + ($i + 1) * $smallIntervalLength;

            if($i == 9)
            {
                $smallIntervalEnd = $endInterval;
            }

            $key = $smallIntervalStart.'_'.$smallIntervalEnd;

            $subInterval = new Interval($smallIntervalStart, $smallIntervalEnd);
            $subInterval->chromosome = $chromosome;
            $subInterval->gene = $gene;

            $this->subIntervals[$key] = $subInterval;
            $parentInterval->addSubInterval($subInterval);
        }
    }


    // ---------------- Writing Files --------------- //

    public function createSubIntervalsFile($location)
    {
        $fileHandler = fopen($location, 'w');

        if($fileHandler == false)
        {
            echo 'Failed to create file';
            return;
        }

        foreach ($this->subIntervals as $key => $value)
        {
            $line = $value->chromosome."\t".$value->getStart()."\t".$value->getEnd()."\t".$value->gene."\t".($value->getLength())."\n";

            fwrite($fileHandler, $line);
        }

        fclose($fileHandler);
    }

    public function createResultFile($location)
    {
        $fileHandler = fopen($location, 'w');

        //fwrite($fileHandler, "{ \"data\": ");
        //fwrite($fileHandler, "["."\n");

        //$results = array();

        foreach ($this->intervals as $key => $value)
        {
            if($value->getMean() == 0 && $value->getStandardDeviation() == 0 && $value->getEntropy() == 0 &&
                $value->getMedianDepth() == 0 && $value->getMaxDepth() == 0)
            {
                continue;
            }

            $results[] = array($value->getMean(), $value->getStandardDeviation(),
                $value->getEntropy(), $value->getStart(), $value->getEnd(),
                $value->getMedianDepth(), $value->getMaxDepth());

            //[0,0,0,3207317,3213438]


            //$line = "[".$value->getMean().",".$value->getStandardDeviation().","
            //    .$value->getEntropy().",".$value->getStart().",".$value->getEnd()."],\n";
            //fwrite($fileHandler, $line);
        }

        $json =  json_encode($results, JSON_NUMERIC_CHECK);

        //fwrite($fileHandler, "]}\n");

        fwrite($fileHandler, $json);

        fclose($fileHandler);
    }


    // ----------------- Entropy Calculation ----------------- //
    public function calculateEntropy()
    {
        foreach ($this->intervals as $interval)
        {
            $interval->calculateMaxDepthSum();
        }

        foreach ($this->intervals as $interval)
        {
            $interval->calculateMiniEntropy();
        }

        foreach ($this->intervals as $interval)
        {
            $interval->calculateEntropy();
        }
    }
}