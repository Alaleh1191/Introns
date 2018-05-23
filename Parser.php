<?php

class Parser
{
    /** @var string line to handle */
    protected $line;

    /** @var string file to parse */
    protected $file;

    /** @var FileHandler handler - used to process the data */
    protected $handler;

    /** @var int lines
     *  Number of maximum lines to read
     *  0 means infinite
     */
    protected $maxLines;

    public function __construct(string $file, FileHandler $handler, int $maxLines = 0)
    {
        $this->file = $file;
        $this->handler = $handler;
        $this->maxLines = $maxLines;

        $this->run();
    }

    /**
     * Run the parser by going line by line and giving the handler
     * the line to process
     *
     * @return void
     */
    protected function run()
    {
        $fileHandle = fopen($this->file, 'r');

        if(!$fileHandle)
        {
            exit('Cannot open the file!');
        }

        $i = 0;

        while(($line = fgets($fileHandle)) !== false && ($this->maxLines == 0 || $i < $this->maxLines))
        {
            $array = $this->handler->processLine($line);
            $this->handler->process($array);

            $i++;
        }

        fclose($fileHandle);
    }

}