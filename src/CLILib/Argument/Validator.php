<?php declare(strict_types=1);

namespace CLILib\Argument;

use CLILib;

class Validator
{
    const FLAG_REQUIRED = 0x0001;

    private $argumentName;
    private $flags;
    private $validator;
    private $default;

    public function flags() : int
    {
        return $this->flags;
    }

    public function default()
    {
        return $this->default;
    }

    public function validator() : \Closure
    {
        return $this->validator;
    }

    public function argumentName() : string
    {
        return $this->argumentName;
    }

    public function __construct($argumentName, $default=null, \Closure $validator=null, $flags=null)
    {
        $this->argumentName = $argumentName;
        $this->flags = $flags;
        $this->default = $default;
        $this->validator = $validator;
    }

    public function validate($input, CLILib\Argument\Iterator $context)
    {
        if (!($input instanceof CLILib\Argument)) {
            if ($this->isRequired()) {
                throw new Exceptions\ArgumentValidationFailedException(
                    $this->missingArgumentExceptionMessage($this->argumentName)
                );
            }

            return $this->default;
        }

        // For a function, we run the arg through the function. It will
        // return a value to be assigned to that argument
        // or it will throw ArgumentValidationFailedException
        if ($this->validator instanceof \Closure) {
            return call_user_func($this->validator, $input, $context);
        } else {
            return $input->value();
        }
    }

    public function isRequired() : bool
    {
        return isFlagSet($this->flags, self::FLAG_REQUIRED);
    }

    protected function missingArgumentExceptionMessage($args) : string
    {
        if (!is_array($args)) {
            $args = [$args];
        }

        $args = array_map(function ($input) {
            return strlen($input) == 1
                ? "-{$input}"
                : "--{$input}"
            ;
        }, $args);

        return sprintf(
            "Argument %s%s is required. See --usage for more information.",
            array_shift($args),
            count($args) > 0
                ? " (".implode(", ", $args).")"
                : ""
        );
    }
}
