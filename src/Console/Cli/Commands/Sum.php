<?php

namespace Cli\Commands;

use Exceptions\InvalidArgumentException;

/**
 * Class Sum
 * Команда вызывается по умолчанию  при вызове  еще  не существующей команды
 * @package Cli\Commands
 */
class Sum extends Command
{

    protected string $description = "Складывает все аругменты которые поданы на вход";

    protected string $commandName = "sum";

    //protected array $validArguments = ["test"];

    //protected array $validOptions = ["log_file" => ["file.log", "123"]];

    public function __construct(array $params)
    {
        parent::__construct($params);
    }

    public function handle(): void
    {
        if ($this->checkHelp()) {
            $this->writeHelpInfo();
        } else {
            $this->checkOptions();
            $this->output->writeln("Sum: " . $this->sum());
        }
    }

    /**
     * Складывает все поступившие аргументы
     * @return int
     */
    private function sum(): int
    {
        $sum = 0;
        foreach ($this->arguments as $argument)
            $sum += (int)$argument;

        return $sum;
    }

    /**
     * Проверка аргументов
     * @throws InvalidArgumentException
     */
    protected function checkArguments(): void
    {
        foreach ($this->arguments as $argument)
            $this->ensureArgumentExists($argument);
    }

    /**
     * Проверка опций и их  аргументов
     * @throws InvalidArgumentException
     */
    protected function checkOptions(): void
    {
        foreach ($this->options as $optionName => $params)
            $this->ensureOptionParamsExists($optionName);
    }


}