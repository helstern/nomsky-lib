<?php namespace Helstern\Nomsky\Grammar\Converters;

use Helstern\Nomsky\Grammar\TestUtils\TestGrammars;

class EbnfToBnfTest extends \PHPUnit_Framework_TestCase
{
    /** @var TestGrammars */
    protected $testGrammars;

    public function getTestGrammars()
    {
        if (is_null($this->testGrammars)) {
            $this->testGrammars = new TestGrammars();
        }

        return $this->testGrammars;
    }

    public function testConvertSimpleTestBooleanLogicGrammar()
    {
        $testGrammars = $this->getTestGrammars();
        $ebnfGrammar  = $testGrammars->ebnfSimpleTestBooleanLogicGrammar();

        $converter = new EbnfToBnf();
        $bnfProductions = $converter->convert($ebnfGrammar);

        $this->assertNotEmpty($bnfProductions, 'expected some bnf productions, received none');
    }
}
