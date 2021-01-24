<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Console\Output;


use Exceptions\CliException;
use Exceptions\InvalidArgumentException;



class StreamOutput extends Output
{
    private $stream;

    /**
     * StreamOutput constructor.
     * @param $stream
     * @throws InvalidArgumentException
     */
    public function __construct($stream)
    {
        if (!\is_resource($stream) || 'stream' !== get_resource_type($stream)) {
            throw new InvalidArgumentException('The StreamOutput class needs a stream as its first argument.');
        }

        $this->stream = $stream;
    }

    /**
     * @return resource
     */
    public function getStream()
    {
        return $this->stream;
    }

    /**
     * {@inheritdoc}
     * @throws CliException
     */
    protected function doWrite($message, $newline) : void
    {
        if ($newline) {
            $message .= PHP_EOL;
        }

        if (false === @fwrite($this->stream, $message)) {
            throw new CliException('Unable to write output.');
        }

        fflush($this->stream);
    }

}
