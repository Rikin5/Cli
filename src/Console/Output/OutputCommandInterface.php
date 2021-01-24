<?php


namespace Console\Output;


interface OutputCommandInterface
{

    const ARGUMENTS = "Arguments:";
    const OPTIONS = "Options:";

    public function write(string $message): void;

    public function writeln(string $message) :void;

    public function writeCommandName(string $commandName) :void;

    public function writeDescription(string $description) : void;

    public function writeValidArguments(array $validArguments) : void;

    public function writeValidOptions(array $validOptions) : void;

    public function writeArguments(array $arguments) : void;

    public function writeOptions(array $options) : void;

}