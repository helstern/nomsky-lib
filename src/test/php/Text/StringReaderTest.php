<?php namespace Helstern\Nomsky\Text;

class StringReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testReadFromEmptyString()
    {
        $reader = new StringReader('');
        $char = $reader->readCharacter();
        $this->assertNull($char);

        $matcher = new WhitespaceMatcher();
        $match = $reader->readTextMatch($matcher);
        $this->assertNull($match);

        $actualException = null;
        try {
            $reader->skip(1);
        } catch (\Exception $e) {
            $actualException = $e;
        }
        $this->assertNotNull($actualException);
    }
}
