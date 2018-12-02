<?php
use CLILib\Argument;
use PHPUnit\Framework\TestCase;

class ArgumentTest extends TestCase
{
    /**
     * Create Argument object and check Name and Value have been set correctly.
     */
    public function testValidPassArgsToConstructor()
    {
        $a = new Argument("j", "something");

        $this->assertEquals("something", $a->value());
        $this->assertEquals("j", $a->name());

        // Test casting to a string
        $this->assertEquals("something", (string)$a);
    }
}
