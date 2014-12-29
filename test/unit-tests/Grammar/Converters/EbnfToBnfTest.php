<?php namespace Helstern\Nomsky\Grammar\Converters;

use Helstern\Nomsky\Grammar\DefaultGrammar;
use Helstern\Nomsky\Grammar\Expressions\OptionalItem;
use Helstern\Nomsky\Grammar\Expressions\OptionalList;
use Helstern\Nomsky\Grammar\Expressions\Sequence;
use Helstern\Nomsky\Grammar\Production\DefaultProduction;
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
        //$this->markTestSkipped('s');

        $testGrammars = $this->getTestGrammars();
        $ebnfGrammar  = $testGrammars->ebnfSimpleTestBooleanLogicGrammar();

        $converter = new EbnfToBnf();
        $bnfProductions = $converter->convert($ebnfGrammar);

        $this->assertNotEmpty($bnfProductions, 'expected some bnf productions, received none');
    }

    /**
     * Expression      := [ "!" ] <Boolean> { BooleanOperator Boolean }
     *
     * Expression      := <GeneratedSymbol-1> <Boolean> <GeneratedSymbol-2>
     * GeneratedSymbol-1 := lambda | "!"
     * GeneratedSymbol-2 := lambda | BooleanOperator Boolean GeneratedSymbol-2
     */
    public function testConvertOneProductionGrammar()
    {
        $testGrammars = $this->getTestGrammars();
        $expressionUtils = $testGrammars->getExpressionUtils();

        $productions = array();

        $leftSide = $expressionUtils->createNonTerminal('Expression');
        $expressionItems = [
            new OptionalItem($expressionUtils->createTerminal('!')),
            $expressionUtils->createNonTerminal('Boolean'),
            new OptionalList(
                $expressionUtils->createSequenceFromSymbols(
                    array(
                        $expressionUtils->createNonTerminal('BooleanOperator'),
                        $expressionUtils->createNonTerminal('Boolean'),
                    )
                )
            )
        ];
        $rightSide = new Sequence(array_shift($expressionItems), $expressionItems);
        $productions[] = new DefaultProduction($leftSide, $rightSide);

        $ebnfGrammar = new DefaultGrammar('simple test boolean logic', $productions);

        $converter = new EbnfToBnf();
        $bnfProductions = $converter->convert($ebnfGrammar);

        $x = 1;
    }
}
