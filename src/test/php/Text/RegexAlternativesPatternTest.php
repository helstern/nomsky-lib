<?php namespace Helstern\Nomsky\Text;

use Helstern\Nomsky\Lexer\TestResources;

class RegexAlternativesPatternTestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @group small
     */
    public function testPatternWithAlternativesMatchesAsAGroup()
    {
        $pattern = new RegexAlternativesPattern(['=',':==']);
        $pcreMatcher = $pattern->anchorAtStart()->utf8()->dotAll()->matchOne();
        $matchReader = new TokenMatchPcreReader('test', $pcreMatcher);

        $reader = (new FileSource(TestResources::getFileObject('nomsky.iso.ebnf')))->createReader();
        $match = $reader->readTextMatch($matchReader);

        $this->assertNull($match, 'Pattern with alternatives should match as a group');
    }
}
