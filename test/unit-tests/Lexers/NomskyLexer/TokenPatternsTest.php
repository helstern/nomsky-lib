<?php namespace Helstern\Nomsky\Lexers\NomskyLexer;

use Helstern\Nomsky\Lexer\TokenStream\LongestMatchCompositeMatcher;
use Helstern\Nomsky\RegExBuilder\RegexBuilder;
use Helstern\Nomsky\Text\String\StringReader;
use Helstern\Nomsky\Tokens\TokenMatch\AnchoredPcreMatcher;
use Helstern\Nomsky\Tokens\TokenMatch\TokenMatch;
use Helstern\Nomsky\Tokens\TokenPattern\RegexStringTokenPattern;

class NomskyTokenPatternsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param int $patternType
     * @return AnchoredPcreMatcher
     */
    public function getWhitespaceMatcher($patternType)
    {
        $pattern = new RegexStringTokenPattern($patternType, '[[:space:]]+');
        $whitespaceMatcher = new AnchoredPcreMatcher($pattern);

        return $whitespaceMatcher;
    }

    /**
     * @small
     * @group small
     */
    public function testStringLiteralPatternMatches()
    {
        $matchers = [
            $this->getWhitespaceMatcher($patternType = 1)
        ];

        $regexBuilder = new RegexBuilder();
        $patterns = new TokenPatterns($regexBuilder);

        $pattern = new RegexStringTokenPattern(2, "'");
        $matchers[] = new AnchoredPcreMatcher($pattern);

        $pattern = new RegexStringTokenPattern(3, '"');
        $matchers[] = new AnchoredPcreMatcher($pattern);

        $pattern = $patterns->buildStringLiteralPattern(4);
        $stringLiteralMatcher = new AnchoredPcreMatcher($pattern);
        $matchers[] = $stringLiteralMatcher;

        $whitespace = '

        ';
        $stringLiteral = '"EBNF defined in
            ""itself""."';

        $expectedStringMatches = array($whitespace, $stringLiteral);
        $actualStringMatches = [];

        $reader = new StringReader($whitespace . $stringLiteral);
        $compositeMatcher = new LongestMatchCompositeMatcher($matchers);
        $match = $compositeMatcher->match($reader);
        while ($match instanceof TokenMatch) {
            $actualStringMatches[] = $match->getText();
            $reader->skip($match->getByteLength());

            $match = $compositeMatcher->match($reader);
        }

        $this->assertEquals($expectedStringMatches, $actualStringMatches, 'string literal pattern matches wrong text');
    }
}
