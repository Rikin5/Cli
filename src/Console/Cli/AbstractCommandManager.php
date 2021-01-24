<?php


namespace Cli;


use Cli\Commands\Command;
use Cli\Commands\Preset;
use Console\Output\OutputInterface;
use Console\Output\StreamOutput;
use Exceptions\CliException;

abstract class AbstractCommandManager
{
    /**
     * Имя команды
     * @var string
     */
    protected string $commandName;

    /**
     * Массив параметров
     * @var array
     */
    protected array $params = [];

    /**
     * Зарегистрированные команды
     * @var array
     */
    private array $commands;

    /**
     * Интерфейс вывода в консоль
     * @var OutputInterface
     */
    protected OutputInterface $output;


    public function __construct(array $commands)
    {
        $this->register();
        $this->initOutput();
        if($this->checkCommands($commands))
        {
            $this->initInfoAllCommand($this->params);
        }else {
            $this->execute($commands);
            $this->initCommand($this->commandName, $this->params);
        }
    }

    /**
     * Проверка на вызов команды
     * @param array $commands
     * @return bool
     */
    protected function checkCommands(array $commands): bool
    {
       return (count($commands) === 1);
    }

    /**
     * Инициализирует имя команды и массив аргументов
     * @param array $commands
     */
    protected function execute(array $commands): void
    {
        array_shift($commands);
        $this->commandName = array_shift($commands);
        $this->params = $commands;
    }

    /**
     * Инициализация поступившей команды
     *
     * @param string $commandName
     * @param array $params
     */
    protected function initCommand(string $commandName, array $params) :void
    {
        try {
            $command = $this->findCommand($commandName, $params);
            $command->handle();
        } catch (CliException $exception) {
            $this->output->write($exception->getMessage(), true);
        }
    }

    /**
     * Вывод описания  по всем зарегистрированным командам
     * @param array $params
     */
    protected function initInfoAllCommand(array $params) :void
    {
        foreach(array_keys($this->commands) as $commandName){
            $command = $this->findCommand($commandName, $params);
            $command->writeHelpInfo();
        }
    }

    /**
     * Поиск команды, в случае  если такой команды  нет вызывается команда по умолчанию
     * @param string $nameCommand
     * @param array $params
     * @return Preset
     */
    protected function findCommand(string $nameCommand, array $params) :Command
    {
        try {
            if (isset($this->commands[$nameCommand])) {
                return new $this->commands[$nameCommand]($params);
            } else {
                return new Preset($params, $nameCommand);
            }
        } catch (CliException $exception) {
            $this->output->write($exception->getMessage(), true);
        }
    }

    /**
     * Инициализация вывода
     * @throws \Exceptions\InvalidArgumentException
     */
    protected function initOutput(): void
    {
        $this->output = new StreamOutput(fopen('php://stdout', 'w'));
    }

    /**
     * Связывает  реализацию команды с её именем
     * @param $className
     * @param $commandName
     * @throws CliException
     */
    protected function bindTo($className, $commandName)
    {
       if(!isset($this->commands[$commandName])) {
           $this->commands[$commandName] = $className;
       }else {
           throw new CliException("Error: Command is already registered");
       }
    }

    /**
     * Здесь можно регистрировать  новые команды
     */
    abstract protected function register(): void;

}