<?php namespace Helstern\Nomsky\Lexer\TokenStream;

use Helstern\Nomsky\Text\String\StringMatch;
use Helstern\Nomsky\Text\TextReader;
use Helstern\Nomsky\Tokens\TokenMatch\PrefixMatcher;
use Helstern\Nomsky\Tokens\TokenPattern\TokenPattern;

class LongestMatchCompositeMatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testLongestMatchIsChosen()
    {
        /** @var TokenPattern $shorterMatchPattern */
        $shorterMatchPattern = $this->getMockBuilder('Helstern\\Nomsky\\Tokens\\TokenPattern\\TokenPattern')
            ->getMockForAbstractClass();
        /** @var TokenPattern $longestMatchPattern */
        $longestMatchPattern = $this->getMockBuilder('Helstern\\Nomsky\\Tokens\\TokenPattern\\TokenPattern')
            ->getMockForAbstractClass();

        $tokenMatchers = [
            new PrefixMatcher($shorterMatchPattern),
            new PrefixMatcher($shorterMatchPattern),
            new PrefixMatcher($longestMatchPattern)
        ];
        $compositeMatcher = new LongestMatchCompositeMatcher($tokenMatchers);

        /** @var TextReader|\PHPUnit_Framework_MockObject_MockObject $mockTextReader */
        $mockTextReader = $this->getMockBuilder('Helstern\\Nomsky\\Text\\TextReader')->getMockForAbstractClass();
        $mockTextReader->expects($this->any())->method('readTextMatch')->willReturnOnConsecutiveCalls(
            new StringMatch('"'),
            null,
            new StringMatch('" a longer match "')
        );
        $mockTextReader->expects($this->any())->method('readCharacter')->willReturn('"');
        $tokenMatch = $compositeMatcher->match($mockTextReader);

        $this->assertEquals('" a longer match "', $tokenMatch->getText(), 'wrong match returned');
    }
}
