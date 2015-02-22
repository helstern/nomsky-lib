<?php namespace Helstern\Nomsky\Tokens\TokenMatch;

use Helstern\Nomsky\Lexer\Resource;
use Helstern\Nomsky\Lexer\TextSource\FileSource;
use Helstern\Nomsky\Tokens\TokenPattern\RegexTokenPattern;

class AnchoredPcreMatcherText extends \PHPUnit_Framework_TestCase
{
    /**
     * @group small
     */
    public function testPatternWithAlternativesMatchesAsAGroup()
    {
        $pattern = new RegexTokenPattern(1, '=|:==');
        $tokenMatcher = new AnchoredPcreMatcher($pattern);

        $reader = (new FileSource(Resource::getFileObject('nomsky.iso.ebnf')))->createReader();
        $match = $reader->readTextMatch($tokenMatcher);

        $this->assertNull($match, 'Pattern with alternatives should match as a group');
    }
}
