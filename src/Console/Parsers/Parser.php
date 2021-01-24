<?php

namespace Parsers;

abstract class Parser implements ParserInterface
{
    protected array $arguments;

    protected array $options;

    public function __construct(array $params)
    {
        $this->setArguments($params);
        $this->setOptions($params);
    }


    protected function setArguments(array $params): void
    {
        $this->arguments = $this->parseArguments($params);
    }

    protected function setOptions(array $params): void
    {
        $this->options = $this->parseOptions($params);
    }

    /**
     * @inheritDoc
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @inheritDoc
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    abstract protected function parseArguments(array $params): array;

    abstract protected function parseOptions(array $params): array;
}