<?php
use CLILib\Message;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    /**
     * Create Message object and check they have been set correctly.
     */
    public function testValidPassArgsToConstructor()
    {

        $m = (new Message("This is my message"))
            ->prependDate(true)
            ->dateFormat('G:i > ')
            ->appendNewLine(false)
            ->foreground("light green")
            ->background("red")
        ;

        $this->assertEquals("This is my message", $m->message);
        $this->assertEquals('G:i > ', $m->dateFormat);
        $this->assertTrue($m->prependDate);
        $this->assertFalse($m->appendNewLine);
        $this->assertEquals('light green', $m->foreground);
        $this->assertEquals('red', $m->background);

        $this->assertEquals(gmdate('G:i > ') . "\e[1;32m\e[41mThis is my message\033[0m", (string)$m);

        // Now change everything and make sure the new values are saved in.
        $m
            ->message("Hi there, friend")
            ->prependDate(false)
            ->dateFormat('Y-m-d H:i > ')
            ->appendNewLine(true)
            ->foreground("cyan")
            ->background(null)
        ;

        $this->assertEquals("Hi there, friend", $m->message);
        $this->assertEquals('Y-m-d H:i > ', $m->dateFormat);
        $this->assertFalse($m->prependDate);
        $this->assertTrue($m->appendNewLine);
        $this->assertEquals('cyan', $m->foreground);
        $this->assertEquals(null, $m->background);

        $this->assertEquals("\e[0;36mHi there, friend\033[0m" . PHP_EOL, (string)$m);

        return $m;

    }

    /**
     * Create Message object and use display method. Check output matches expected
     * @depends testValidPassArgsToConstructor
     */
    public function testDisplay($m) {
        $temp = tempnam(sys_get_temp_dir(), 'CLILibTest');
        $fp = fopen($temp, 'w');
        $m->display($fp);
        fclose($fp);

        // make sure contents of the tmp file matches
        $this->assertEquals((string)$m, file_get_contents($temp));

        return $m;
    }

    public function testBadForeground() {
        $this->expectException(\Exception::class);
        (new Message)->foreground("banana");
    }

    public function testBadBackground() {
        $this->expectException(\Exception::class);
        (new Message)->background("cabbage");
    }
}
