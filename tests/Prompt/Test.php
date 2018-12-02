<?php
use CLILib\Prompt;
use PHPUnit\Framework\TestCase;

class PromptTest extends TestCase
{

    public function testDefaultValueInput()
    {
        $temp = tempnam(sys_get_temp_dir(), 'CLILibTest');
        file_put_contents($temp, "");

        $fp = fopen($temp, 'r');
        $input = Prompt::display(PHP_EOL . "PROMPT TEST #1", null, gmdate("Y"), null, "", $fp);
        fclose($fp);

        $this->assertEquals(gmdate("Y"), $input);
    }

    public function testValidInput()
    {
        $temp = tempnam(sys_get_temp_dir(), 'CLILibTest');
        file_put_contents($temp, "fred");

        $fp = fopen($temp, 'r');
        $input = Prompt::display(PHP_EOL . "PROMPT TEST #2", null, null, null, "", $fp);

        $this->assertEquals("fred", $input);

        return $fp;
    }

    /**
    * @depends testValidInput
    **/
    public function testNonSTDINSilentInput($fp)
    {
        $this->expectException(\Exception::class);
        $input = Prompt::display(PHP_EOL . "PROMPT TEST #3", Prompt::FLAG_SILENT, null, null, "", $fp);
        fclose($fp);
    }

}
