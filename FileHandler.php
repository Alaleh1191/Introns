<?php

interface FileHandler
{
    /**
     * @param $array array
     * Array to be processed
     *
     * @return void
     */
    public function process($array);

    /**
     * @param $line string
     * Turn current lines into an array
     *
     * @return array
     */
    public function processLine($line);
}