<?php namespace Helstern\Nomsky\Text;

class WhitespaceMatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testMatchesWhitespaceAtStartOfString()
    {
        $expectedMatch = " \r\n \n ";
        $string = $expectedMatch.'not whitespace';

        $matcher = new WhitespaceMatcher();
        $actualMatch = $matcher->match($string);
        $this->assertEquals($expectedMatch, $actualMatch, 'does not match whitespace at start');

        // only with spaces
        $expectedMatch = '     ';
        $string = $expectedMatch.'not whitespace';
        $actualMatch = $matcher->match($string);
        $this->assertEquals($expectedMatch, $actualMatch, 'does not match whitespace at start');
    }

    public function testDoesNotMatchWithoutWhitespaceAtStartOfString()
    {
        $string = 'not whitespace' . " \r\n \n ";

        $matcher = new WhitespaceMatcher();
        $actualMatch = $matcher->match($string);
        $this->assertNull($actualMatch, 'should not match without whitespace');
    }
}
