<?php
namespace CLILib;

class Argument
{
    private $name;
    private $value;

    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Returns the $name property
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Returns the $value property
     *
     * @return string
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * Returns the $value property when casting Argument to string
     *
     * @return string
     */
    public function __toString() {
        return (string)$this->value;
    }
}
