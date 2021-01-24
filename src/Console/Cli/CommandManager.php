<?php


namespace Cli;


use Exceptions\CliException;

class CommandManager extends AbstractCommandManager
{

    public function __construct(array $commands)
    {
        parent::__construct($commands);
    }


    /**
     * @inheritDoc
     */
    protected function register(): void
    {
        try {
            $this->bindTo("Cli\Commands\Preset", "preset");
            $this->bindTo("Cli\Commands\Sum", "sum");
        }catch (CliException $exception){
            $this->output->write($exception->getMessage());
        }
    }




}