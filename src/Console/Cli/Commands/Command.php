<?php


namespace Cli\Commands;

use Exceptions\CliException;
use Console\Output\OutputCommandInfo;
use Console\Output\OutputCommandInterface;

use Exceptions\InvalidArgumentException;
use Parsers\CommandLineParser;

abstract class Command implements CommandInterface
{

    /**
     * @var string Имя команды
     */
    protected string $commandName;

    /**
     * @var array Аргументы полученные из командной строки
     */
    protected array $arguments;

    /**
     * @var array Опции полученные из командной строки
     */
    protected array $options;

    /**
     * @var string Описание команды
     */
    protected string $description;

    /**
     * @var array Массив допустимых аргументов
     * Пример: $validArguments = ["verbose","overwrite"];
     */
    protected array $validArguments = [];

    /**
     * @var array массив допустимых опций
     * Пример:  $validOptions = ["methods"=>["create","update","delete"],"paginate"=>50];
     */
    protected array $validOptions = [];

    /**
     * @var OutputCommandInfo|OutputCommandInterface Вывод сообщений в командную строку
     */
    protected OutputCommandInterface $output;

    /**
     * Имя ключевого аргумента
     */
    const ARG_HELP = "help";

    public function __construct(array $params)
    {
        $this->initOutput();
        $parser = new CommandLineParser($params);
        $this->arguments = $parser->getArguments();
        $this->options = $parser->getOptions();
    }

    /**
     * Возвращает имя команды
     * @return string
     */
    public function getCommandName(): string
    {
        return $this->commandName;
    }

    /**
     * Возвтращает описание команды
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Возвращает разрешенные  аргументы команды
     * @return array
     */
    public function getValidArguments(): array
    {
        return $this->arguments;
    }

    /**
     * Возвращает разрешенные опции команды
     * @return array
     */
    public function getValidOptions(): array
    {
        return $this->options;
    }

    /**
     * Инициализирует реализацию вывода в консоль
     */
    protected function initOutput() : void
    {
        $this->output = new OutputCommandInfo();
    }

    /**
     * Проверка на  help
     * @return bool
     */
    protected function checkHelp()
    {
        if (count($this->arguments) === 1 and ($this->arguments[0] === self::ARG_HELP) and empty($this->options))
            return true;
    }

    /**
     * Вывод в консоль краткое описание  команды  и её параметры
     */
    public function writeHelpInfo(): void
    {
        $this->output->writeCommandName($this->commandName);
        $this->output->writeln('');
        $this->output->writeDescription($this->description);
        $this->output->writeln('');
        $this->output->writeValidArguments($this->validArguments);
        $this->output->writeln('');
        $this->output->writeValidOptions($this->validOptions);
    }


    /**
     * Сверка  параметров из консольной строки с разрешенными
     *
     * @param string $paramName
     * @param array $validParams
     * @return bool
     * @throws InvalidArgumentException
     */
    private function ensureParamExists(string $paramName, array $validParams) :bool
    {
        foreach ($validParams as $validParam) {
            if ($validParam === $paramName) return true;
        }
        throw new InvalidArgumentException('Param with name "' . $paramName . '" is not defined!');

    }

    /**
     * Проверка валидности аргументов
     */
    abstract protected function checkArguments(): void;

    /**
     * Проверка валидности опций
     */
    abstract protected function checkOptions(): void;

    /**
     * Здесь описывается основная логика команды
     * @return mixed
     */
    abstract public function handle() : void ;

    /**
     * Сверка  аргументов из консольной строки с разрешенными
     * @param string $argumentName
     * @return bool
     * @throws InvalidArgumentException
     */
    protected function ensureArgumentExists(string $argumentName): bool
    {
        try {
            return $this->ensureParamExists($argumentName, $this->validArguments);
        } catch (InvalidArgumentException $exception) {
            throw new InvalidArgumentException("Error arguments: " . $exception->getMessage());
        }
    }

    /**
     * Сверка  опций из консольной строки с разрешенными
     * @param string $optionName
     * @return bool
     * @throws InvalidArgumentException
     */
    protected function ensureOptionExists(string $optionName): bool
    {
        if (isset($this->validOptions[$optionName])) {
            return true;
        } else {
            throw new InvalidArgumentException('Option with name "' . $optionName . '" is not defined!');
        }
    }

    /**
     * Сверка  опций и их параметров из консольной строки с разрешенными
     *
     * @param string $optionName
     * @return bool
     * @throws InvalidArgumentException
     */
    protected function ensureOptionParamsExists(string $optionName): bool
    {
        if ($this->ensureOptionExists($optionName)) {
            $validParams = is_array($this->validOptions[$optionName]) ?
                $this->validOptions[$optionName] : [$this->validOptions[$optionName]];

            $params = is_array($this->options[$optionName]) ?
                $this->options[$optionName] : [$this->options[$optionName]];

            try {
                foreach ($params as $paramName)
                    $this->ensureParamExists($paramName, $validParams);

                return true;
            } catch (InvalidArgumentException $exception) {
                throw new InvalidArgumentException("Error options: " . $exception->getMessage());
            }
        }
    }
}