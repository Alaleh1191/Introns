<?php

class Interval
{
    /** @var int $start
     * Start of the interval
     */
    protected $start;

    /** @var int $end
     * End of the interval
     */
    protected $end;

    /**
     * @var int $length
     * Interval Length
     */
    protected $length;

    /** @var double $mean
     * Mean of the interval
     */
    protected $mean;

    /** @var double $std
     * Standard Deviation of the interval
     */
    protected $std;

    /**
     * @var int
     * Median depth value for the interval
     */
    protected $medianDepth;

    /** @var double $entropy  */
    protected $entropy;

    /** @var Interval[] $subIntervals */
    protected $subIntervals;

    /** ---- Superfluous Information ---- */
    public $chromosome;
    public $gene;

    // ---- Helpful variables for entropy calculation ---- //

    /** @var int $maxDepth
     * Maximum Depth for this interval
     */
    protected $maxDepth;

    // ----- Helpful variable for median calculation ----- //
    protected $medianRemainingCount;

    /** @var int $subIntervalsSum */
    protected $subIntervalsDepthSum;

    public function __construct($start, $end)
    {
        $this->start = $start + 0; // Converts it to a number
        $this->end = $end + 0; // Converts it to a number

        $this->length = $this->getEnd() - $this->getStart();

        $this->medianRemainingCount = floor($this->length / 2);

        $this->mean = 0;
        $this->std  = 0;
        $this->maxDepth = 0;
    }


    public function calculateMean($depth, $basesAtDepth, $intervalLength)
    {
        $meanForLine = ($depth * $basesAtDepth) / $intervalLength;

        $this->mean += $meanForLine;
    }

    public function calculateStandardDeviation($depth, $basesAtDepth, $intervalLength)
    {
        $stdForLine = $basesAtDepth * (pow($depth - $this->mean,2)) / $intervalLength;

        /*echo '#bases:'.$basesAtDepth.PHP_EOL;
        echo 'Depth:'.$depth.PHP_EOL;
        echo 'Mean:'.$this->mean.PHP_EOL;

        echo ($depth - $this->mean).PHP_EOL;
        echo (pow($depth - $this->mean, 2)).PHP_EOL;

        echo 'Interval Length:'.$intervalLength.PHP_EOL.PHP_EOL;*/

        $this->std += $stdForLine;
    }

    public function addSubInterval(Interval $interval)
    {
        $this->subIntervals[] = $interval;
    }

    // ---------------------- Median -------------------- //

    public function calculateMedian($bases, $depth)
    {
        $this->medianRemainingCount -= $bases;

        if($this->medianRemainingCount <= 0)
        {
            $this->medianDepth = $depth;

            // Reset to high number so this doesn't get set again.
            $this->medianRemainingCount = $this->getLength();
        }
    }


    // ---------------------- Entropy ------------------- //

    /**
     * @param $depth
     *
     * Used in sub intervals to calculate the maximum depth
     */
    public function calculateMaxDepth($depth)
    {
        $this->maxDepth = max($this->maxDepth, $depth);
    }

    /**
     * Used in the parent interval to sum all sub intervals' maxDepth value
     */
    public function calculateMaxDepthSum()
    {
        $sum = 0;

        foreach ($this->getSubIntervals() as $key => $subInterval)
        {
            $sum += $subInterval->getDepth();
        }

        $this->subIntervalsDepthSum = $sum;
    }

    /**
     * Used in the parent interval to set the sub Intervals mini entropy
     */
    public function calculateMiniEntropy()
    {
        foreach ($this->getSubIntervals() as $key => $subInterval)
        {
            if($this->subIntervalsDepthSum == 0)
            {
                $subInterval->entropy = 0;
                continue;
            }

            $p = $subInterval->getDepth() / $this->subIntervalsDepthSum;

            if($p != 0)
            {
                $subInterval->entropy = -1 * ($p * log($p, 2));
            }
            else
            {
                $subInterval->entropy = 0;
            }
        }
    }

    /**
     * Used in the parent interval to sum all of the sub intervals' mini entropy
     */
    public function calculateEntropy()
    {
        foreach ($this->getSubIntervals() as $key => $subInterval)
        {
            $this->entropy += $subInterval->entropy;
        }
    }


    /**
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    public function getEnd()
    {
        return $this->end;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function getSubIntervals()
    {
        return $this->subIntervals;
    }

    public function getDepth()
    {
        return $this->maxDepth;
    }

    public function getMean()
    {
        return $this->mean;
    }

    public function getStandardDeviation()
    {
        return $this->std;
    }

    public function getEntropy()
    {
        return $this->entropy;
    }

    public function getMaxDepth()
    {
        return $this->maxDepth;
    }

    public function getMedianDepth()
    {
        return $this->medianDepth;
    }
}