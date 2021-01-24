<?php


namespace Parsers;


class LinuxCommandLineParser extends Parser
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
            if (strlen($param = $this->parseParams($param)) > 0) {
                $arguments[] = $param;
            }
        }
        return $arguments;
    }

    /**
     * Ищет в строке параметры  заключенные  в {}
     *
     * @param string $params
     * @return string
     */
    private function parseParams(string $params): string
    {
        preg_match('/^(?:(?!\[).)+$/', $params, $matches);
        if (empty($matches)) {
            return "";
        } else {
            return str_replace(["{", "}"], "", array_shift($matches));
        }
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
            preg_match('/\[(.*?)\]/', $param, $matches);
            if (!empty($matches)) {
                list($nameOption, $arg) = explode("=", $matches[1]);

                if (isset($options[$nameOption])) {
                    is_array($options[$nameOption]) ?
                        array_push($options[$nameOption], $arg) :
                        $options[$nameOption] = [$options[$nameOption], $arg];
                } else {
                    $options[$nameOption] = $arg;
                }
            }
        }

        return $options;
    }
}