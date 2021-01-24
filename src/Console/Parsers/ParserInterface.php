<?php


namespace Parsers;


interface ParserInterface
{
    /**
     * Вернуть массив найденных аргументов
     * @return array
     */
    public function getArguments(): array;

    /**
     * Вернуть массив найденных опций
     * @return array
     */
    public function getOptions(): array;

}