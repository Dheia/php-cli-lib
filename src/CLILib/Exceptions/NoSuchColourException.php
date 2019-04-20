<?php declare(strict_types=1);

namespace CLILib\Exceptions;

class NoSuchColourException extends CLILibException
{
    public function __construct(string $colour, array $options, $code = 0, \Exception $previous = null)
    {
        return parent::__construct(sprintf(
            "Colour '%s' does not exist. Options available: ",
            $colour,
            implode(', ', $options)
        ), $code, $previous);
    }
}
