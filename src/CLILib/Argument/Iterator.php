<?php

namespace CLILib\Argument;
use CLILib;

class Iterator implements \Iterator, \Countable
{
    private $args = [];
    private $keys = [];
    private $position = 0;

    public function __construct(array $args = null, $ignoreFirst = true)
    {
        // Constructor can accept an array of arguments,
        // however if it's null, try to use ARGV instead
        // Need to assign to a new variable, since this function
        // would be destructive to $argv otherwise.
        if (is_null($args)) {
            global $argv;
            $args = $argv;
        }

        $start = 0;
        if ($ignoreFirst == true) {
            array_shift($args);
        }

        // Reconstruct the args string
        $string = implode($args, ' ');
        $matches = [];

        /**
         * Credit to "Jonathan Leffler" from Stack Overflow
         * for this regex (http://stackoverflow.com/a/13141314)
         */
        // 1 - Fixed <name> capturing group so it handles hyphens.
        preg_match_all(
            '@(?:-{1,2}|\/)(?<name>[\w-]+)(?:(?:[:=]|\s+)(?:(?<value>[^-\s"][^"\s]+)|(?:"(?<value_in_quotes>[^"]+?)")))?@i',
            $string,
            $matches,
            PREG_SET_ORDER
        );

        foreach ($matches as $arg) {
            $name = $arg['name'];

            $value = true;
            if(isset($arg['value_in_quotes'])) {
                $value = $arg['value_in_quotes'];

            } elseif(isset($arg['value'])) {
                $value = $arg['value'];
            }

            $this->args[] = new CLILib\Argument($name, $value);
            $this->keys[] = $name;
        }

        return true;
    }
    /**
     * Look through the args array for a particular value. If $name is an array, it
     * will look through until it finds a match and return the first one.
     *
     * @param  string|array $name
     * @return array
     */
    public function find($names) : ?CLILib\Argument
    {
        if (!is_array($names)) {
            $names = [$names];
        }

        foreach ($names as $name) {
            if (in_array($name, $this->keys)) {
                return $this->args[array_search($name, $this->keys)];
            };
        }

        return null;
    }

    public function rewind() : void
    {
        $this->position = 0;
    }

    public function current() : CLILib\Argument
    {
        return $this->args[$this->position];
    }

    public function key() : int
    {
        return $this->position;
    }

    public function next() : void
    {
        ++$this->position;
    }

    public function valid() : bool
    {
        return isset($this->args[$this->position]);
    }

    public function count() : int
    {
        return count($this->keys);
    }
}
