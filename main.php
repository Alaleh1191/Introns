<?php

// Include Interface
include_once 'FileHandler.php';

/**
 * Includes all the classes in the current directory
 */
foreach (glob("*.php") as $filename)
{
    include_once $filename;
}

$coverageFile = $argv[1];
$entropyFile  = $argv[2];

$maxLines = 0;

// ------------------- Setup Intervals ---------------------- //
/** @var IntervalsHandler $intervalsHandler
 *
 * Setup the Interval Handler class
 */
$intervalsHandler = new IntervalsHandler();

/** @var Parser $parser
 *  Goes line by line to create the intervals
 */
$parser = new Parser('Files/'.$coverageFile, $intervalsHandler, $maxLines);


// ------------------- Step 1: Mean, StdDev, Median Depth ---------------------- //

/**
 * Handler for calculating the mean
 */
$meanHandler = new MeanHandler($intervalsHandler);

/**
 * Goes through each line of the file and gives it to the
 * MeanHandler to compute the mean for each interval
 */
$parser = new Parser('Files/'.$coverageFile, $meanHandler, $maxLines);

/**
 * Handler for calculating the Standard Deviation
 */
$standardDeviationHandler = new StandardDeviationHandler($intervalsHandler);

/**
 * Goes through each line of the file and gives it to the
 * StandardDeviationHandler to compute the mean for each interval
 */
$parser = new Parser('Files/'.$coverageFile, $standardDeviationHandler, $maxLines);

/**
 * Handler for calculating the Median Depth
 */
$medianHandler = new MedianHandler($intervalsHandler);

/**
 * Goes through each line of the file and gives it to the
 * Median Handler to compute the mean for each interval
 */
$parser = new Parser('Files/'.$coverageFile, $medianHandler, $maxLines);

/**
 * Handler for calculating the Median Depth
 */
$maxDepthHandler = new MaxDepthHandler($intervalsHandler);

/**
 * Goes through each line of the file and gives it to the
 * MaxDepth Handler to compute the mean for each interval
 */
$parser = new Parser('Files/'.$coverageFile, $maxDepthHandler, $maxLines);



$intervalsHandler->createSubIntervalsFile('Files/'.$coverageFile.'_subInterval.bed');

// --------------------- Step 2: Entropy ---------------------

//print_r($intervals);

/**
 * Sets up the class for getting the max depth value for each sub interval
 */
$entropyParsingHandler = new EntropyStep2ParsingHandler($intervalsHandler);

/**
 * Goes though each line of the file
 */
$parser = new Parser('Files/'.$entropyFile, $entropyParsingHandler, 60);

$intervalsHandler->calculateEntropy();

$intervalsHandler->createResultFile('Files/'.$coverageFile.'_result.txt');

if($maxLines > 0)
{
    print_r($intervalsHandler->getIntervalsArray());
    //print_r($intervalsHandler->getSubIntervalsArray());
}





//print_r($intervalsHandler->getSubIntervalsArray());


//print_r($entropyIntervalHandler->getAllIntervals());

