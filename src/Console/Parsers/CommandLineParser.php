<?php


namespace Parsers;


class CommandLineParser extends Parser
{

    public function __construct(array $params)
    {
        parent::__construct($params);
    }


    /**
     * Возвращает массив аргументов
     *
     * @param array $params
     * @return array
     */
    protected function parseArguments(array $params): array
    {
        $arguments = [];
        foreach ($params as $param) {
            $arguments = array_merge($arguments, $this->parseParams($param));
        }

        return $arguments;
    }

    /**
     * Ищет в строке параметры  заключенные  в {}
     *
     * @param string $params
     * @return array
     */
    private function parseParams(string $params): array
    {
        preg_match('/(?<!=){(.*?)}/', $params, $matches);

        $params = (!empty($matches)) ?
            explode(",", $matches[1]) : [];

        return $params;
    }


    /**
     * Возвращает массив опций
     *
     * @param array $params
     * @return array
     */
    protected function parseOptions(array $params): array
    {
        $options = [];
        foreach ($params as $param) {
            preg_match('/(?<!{)\[(.*?)\]/', $param, $matches);
            if (!empty($matches)) {
                list($nameOption, $arg) = explode("=", $matches[1]);
                $options[$nameOption] = $this->parseParams($arg) ?: $arg;
            }
        }
        return $options;
    }
}