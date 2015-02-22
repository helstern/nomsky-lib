<?php namespace Helstern\Nomsky\Tokens\TokenMatch;

use Helstern\Nomsky\Lexer\TestResources;
use Helstern\Nomsky\Lexer\TextSource\FileSource;
use Helstern\Nomsky\Tokens\TokenPattern\RegexAlternativesTokenPattern;

class AnchoredPcreMatcherText extends \PHPUnit_Framework_TestCase
{
    /**
     * @group small
     */
    public function testPatternWithAlternativesMatchesAsAGroup()
    {
        $pattern = new RegexAlternativesTokenPattern(1, ['=',':==']);
        $tokenMatcher = new AnchoredPcreMatcher($pattern);

        $reader = (new FileSource(TestResources::getFileObject('nomsky.iso.ebnf')))->createReader();
        $match = $reader->readTextMatch($tokenMatcher);

        $this->assertNull($match, 'Pattern with alternatives should match as a group');
    }
}
