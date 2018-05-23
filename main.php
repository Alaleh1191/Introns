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


// ------------------- Step 1: Mean and StdDev ---------------------- //

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

$intervalsHandler->createSubIntervalsFile('Files/'.$coverageFile.'_subInterval.bed');

if($maxLines > 0)
{
    print_r($intervalsHandler->getIntervalsArray());
    print_r($intervalsHandler->getSubIntervalsArray());
}

// --------------------- Step 2: Entropy ---------------------

//print_r($intervals);

/**
 * Sets up the class for getting the max depth value for each sub interval
 */
$entropyParsingHandler = new EntropyStep2ParsingHandler($intervalsHandler);

/**
 * Goes though each line of the file
 */
$parser = new Parser('Files/'.$entropyFile, $entropyParsingHandler, $maxLines * 100);

$intervalsHandler->calculateEntropy();

$intervalsHandler->createResultFile('Files/'.$coverageFile.'_result.txt');





//print_r($intervalsHandler->getSubIntervalsArray());


//print_r($entropyIntervalHandler->getAllIntervals());

