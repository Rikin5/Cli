<?php


namespace Console\Output;

/**
 * Class OutputCommandInfo
 * Здесь описаны базовые функции вывода для команд
 * @package Console\Output
 */
class OutputCommandInfo implements OutputCommandInterface
{

    const ESCAPE_WITH_NDASH = "    -  ";
    const ESCAPE = "    ";
    const CALLED_COMMAND = "Called command: ";
    const DESCRIPTION = "Description: ";
    const VALID_ARGS = "Valid arguments: ";
    const VALID_OPTIONS = "Valid options: ";

    private Output $output;

    public function __construct()
    {
        $this->output = new StreamOutput(fopen('php://stdout', 'w'));
    }

    /**
     * Вывод в консоль
     * @param string $message
     */
    public function write(string $message): void
    {
        $this->output->write($message);
    }

    /**
     * Вывод в консоль с переходом на новую строку
     * @param string $message
     */
    public function writeln(string $message): void
    {
        $this->output->write($message, true);
    }

    /**
     * Вывод всех аргументов команды
     * @param array $arguments
     * @param string $header надпись пред списком аргументов
     */
    public function writeArguments(array $arguments, string $header = self::ARGUMENTS) : void
    {
        if (!empty($arguments)) {
            $this->writeln($header);
            foreach ($arguments as $argument)
                $this->writeArgument($argument);
        }
    }

    /**
     * Вывод  всех опций команды
     * @param array $options
     * @param string $header
     */
    public function writeOptions(array $options, string $header = self::OPTIONS) : void
    {
        if (!empty($options)) {
            $this->writeln($header);
            foreach ($options as $name => $args) {
                $this->writeln(self::ESCAPE_WITH_NDASH . $name);

                is_array($args) ?
                    $this->writeOptionArg($args) :
                    $this->writeln(self::ESCAPE . self::ESCAPE_WITH_NDASH . $args);
            }
        }
    }

    /**
     * Вывод всех аргументов опции
     * @param array $args
     */
    public function writeOptionArg(array $args):void
    {
        foreach ($args as $arg)
            $this->writeln(self::ESCAPE.self::ESCAPE_WITH_NDASH.$arg);
    }

    /**
     * Вывод имени команды
     * @param string $commandName
     */
    public function writeCommandName(string $commandName):void
    {
        $this->writeln(self::CALLED_COMMAND.$commandName);
    }

    /**
     * Вывод имени аргумента
     * @param string $argumentName
     */
    public function writeArgument(string $argumentName) : void
    {
        $this->writeln(self::ESCAPE_WITH_NDASH.$argumentName);
    }

    /**
     * Вывод описания
     *
     * @param string $description
     * @param string $header
     */
    public function writeDescription(string $description, string $header = self::DESCRIPTION) : void
    {
        $this->writeln($header);
        $this->writeln($description);
    }

    /**
     * Вывод всех аргументов которые принимает команда
     *
     * @param array $validArguments
     * @param string $header
     */
    public function writeValidArguments(array $validArguments, string $header = self::VALID_ARGS) : void
    {
        $this->writeArguments($validArguments, $header);
    }

    /**
     * Вывод всех опций которые  принимает команда
     * @param array $validOptions
     * @param string $header
     */
    public function writeValidOptions(array $validOptions, string $header = self::VALID_OPTIONS): void
    {
        $this->writeOptions($validOptions, $header);
    }

}