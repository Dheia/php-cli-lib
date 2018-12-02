<?php
use CLILib\Argument\Iterator;
use PHPUnit\Framework\TestCase;

class IteratorTest extends TestCase
{
    /**
     * Create iterator using array of argments and check they have been set correctly.
     */
    public function testValidPassArgsToConstructor()
    {
        $it = new Iterator([
            '--hithere',
            '-i',
            '-c cheese',
            '--database myDB',
            '-h:local:host',
            '--lots-of-hyphens',
            '--with-hyphen=alrighty',
        ], false);

        $this->assertEquals(7, iterator_count($it));
        $this->assertTrue($it->find('hithere')->value());
        $this->assertEquals('cheese', $it->find('c')->value());

        // 1 - Added tests to check for hyphens in argument names
        $this->assertTrue($it->find('lots-of-hyphens')->value());
        $this->assertEquals('alrighty', $it->find('with-hyphen')->value());

        // Since iterator_count() was used, the iterator position should be at the end.
        $this->assertEquals(7, $it->key());

        // Return to start and check the first item is "hithere" with a value of true
        $it->rewind();
        $this->assertEquals(0, $it->key());
        $this->assertEquals('hithere', $it->current()->name());
        $this->assertTrue($it->current()->value());
    }

    /**
     * This test checks to see that an empty array state is handled properly
     */
    public function testEmptyArgumentArray()
    {
        $it = new Iterator([], false);
        $this->assertFalse($it->find('hithere'));
        $this->assertEquals(0, $it->count());
    }

    /**
     * This test set the "ignoreFirst" property to true and check that the first item (file name) is ignored
     */
    public function testValidIgnoreFirstArg()
    {
        $it = new Iterator([
            '../blah/blah',
            '--hithere',
        ]);
        $this->assertEquals(1, iterator_count($it));
        $this->assertFalse($it->find('../blah/blah'));
    }

    /**
     * This test seeds the global $argv array with some data to emulate receiving data from the command line
     */
    public function testValidARGV()
    {
        // Seed the $argv array
        global $argv;
        $argv = [
            '../blah/blah',
            '--hithere',
            '-i',
            '-c',
            'cheese',
            '-p:\Users\pointybeard\Sites\shellargs\\',
        ];

        $it = new Iterator();
        $this->assertEquals(4, iterator_count($it));
        $this->assertTrue($it->find('hithere')->value());
        $this->assertEquals('\Users\pointybeard\Sites\shellargs\\', $it->find('p')->value());
    }

    /**
     * Give an argument string to the constructor
     */
    public function testValidArgString()
    {
        $it = new Iterator(['--hithere -i -c cheese'], false);
        $this->assertEquals(3, iterator_count($it));

        return $it;
    }

    /**
     * This test checks that the find method behaves correctly when passing an array of
     * argument names.
     */
    public function testValidFindArgumentArray()
    {
        $it = new Iterator(['--config=/path/to/file --help'], false);
        $this->assertEquals('/path/to/file', $it->find(['c', 'config'])->value());
        $this->assertTrue($it->find(['usage', 'h', 'help'])->value());

        return $it;
    }

    /**
     * @depends testValidFindArgumentArray
     */
    public function testInvalidFindArgumentArray($it)
    {
        $this->assertFalse($it->find(['f', 'file']));
    }
}
