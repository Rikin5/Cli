<?php

namespace Cli\Commands;



use Exceptions\CliException;

/**
 * Class Preset
 * Команда вызывается по умолчанию  при вызове  еще  не существующей команды
 * @package Cli\Commands
 */
class Preset extends Command
{

    protected string $description = "У команды нет имени...";


    //protected array $validArguments = ["test"];

    //protected array $validOptions = ["log_file" => ["file.log", "123"]];

    public function __construct(array $params, string $commandName = "preset")
    {
        parent::__construct($params);
        $this->commandName = $commandName;
    }

    /**
     * @inheritDoc
     */
    public function handle() :void
    {
        $this->checkHelp() ? $this->writeHelpInfo() : $this->writeAbout();

    }

    /**
     * Выводит в консоль имя и параметры команды с которыми она была вызванна
     */
    public function writeAbout():void
    {
        $this->output->writeCommandName($this->commandName);
        $this->output->writeln('');
        $this->output->writeArguments($this->arguments);
        $this->output->writeln('');
        $this->output->writeOptions($this->options);
    }

    /**
     * Проверка аргументов
     * @throws \Exceptions\InvalidArgumentException
     */
    protected function checkArguments(): void
    {
        foreach ($this->arguments as $argument)
            $this->ensureArgumentExists($argument);
    }

    /**
     * Проверка опций и их  аргументов
     * @throws \Exceptions\InvalidArgumentException
     */
    protected function checkOptions(): void
    {
        foreach ($this->options as $optionName => $params)
            $this->ensureOptionParamsExists($optionName);
    }


}