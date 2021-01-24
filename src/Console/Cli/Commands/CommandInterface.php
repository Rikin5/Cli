<?php


namespace Cli\Commands;


interface CommandInterface
{

    public function getCommandName(): string;

    public function getDescription(): string;

    public function getValidArguments(): array;

    public function getValidOptions(): array;

    public function handle() : void;

    public function writeHelpInfo(): void;

}