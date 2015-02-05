<?php namespace Helstern\Nomsky\TextMatch;

class RegexPatternBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildValidRegexPattern()
    {
        $builder = new RegexPatternBuilder();
        $builder->addNamedPattern(0, ',');
        $builder->addNamedPattern(1, 'o');
        $builtPattern = $builder->build();

        $matches = array();
        $textToMatch = 'are you, louie,, my , louie';
        preg_match_all($builtPattern, $textToMatch, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        $this->assertNotEmpty($matches, 'Pattern should match at least once');
        foreach ($matches as $match) {
            $this->assertNotEmpty($match, 'There should not be an empty match');
        }
    }
}
