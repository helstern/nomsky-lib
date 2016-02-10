<?php namespace Helstern\Nomsky\Lexer;

use Helstern\Nomsky\Text\TokenStringMatch;

class LongestMatchWinsStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @group small
     */
    public function testLongestMatchIsChosen()
    {
        $matches = [
            'match',
            'long match'
        ];
        $tokenMatchers = [];
        foreach ($matches as $match) {
            $match = new TokenStringMatch('a', $match);
            $tokenMatchers[] = new StubTokenMatchReader($match);
        }

        $clazz = 'Helstern\\Nomsky\\Lexer\\TextReader'; //TextReader::class
        /** @var TextReader $reader */
        $reader = $this->getMockBuilder($clazz)->getMockForAbstractClass();

        $strategy = new LongestMatchWinsStrategy();
        $actualMatch = $strategy->match($reader, $tokenMatchers);

        $this->assertEquals('long match', $actualMatch->getText());
    }
}
