<?php

namespace Console\Output;

interface OutputInterface
{


    /**
     * Writes a message to the output.
     *
     * @param string $messages The message
     * @param bool $newline Whether to add a newline
     */
    public function write($messages, $newline = false);


}