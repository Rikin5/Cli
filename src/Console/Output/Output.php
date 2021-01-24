<?php


namespace Console\Output;


abstract class Output implements OutputInterface
{


    /**
     * {@inheritdoc}
     */
    public function write($message, $newline = false): void
    {
        $this->doWrite($message, $newline);
    }

    /**
     * Writes a message to the output.
     *
     * @param string $message A message to write to the output
     * @param bool $newline Whether to add a newline or not
     */
    abstract protected function doWrite($message, $newline): void;


}
