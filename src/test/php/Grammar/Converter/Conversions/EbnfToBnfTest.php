<?php namespace Helstern\Nomsky\Grammar\Converter\Conversions;

use Helstern\Nomsky\Grammar\Converter\Conversions;
use Helstern\Nomsky\Grammar\StandardGrammar;
use Helstern\Nomsky\Grammar\Expressions\Optional;
use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammar\Expressions\Concatenation;
use Helstern\Nomsky\Grammar\Production\StandardProduction;
use Helstern\Nomsky\Grammar\Production\Production;
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
//        $this->markTestSkipped('s');

        $testGrammars = $this->getTestGrammars();
        $ebnfGrammar  = $testGrammars->ebnfSimpleTestBooleanLogicGrammar();

        $converter = new Conversions();
        $bnfProductions = $converter->ebnfToBnf($ebnfGrammar);

        $this->assertNotEmpty($bnfProductions, 'expected some bnf productions, received none');
    }

    /**
     *
     * Expression      := [ "!" ] <Boolean> { BooleanOperator Boolean }
     * generates the 3 new productions in the following order:
     *
     * Expression      := <GeneratedSymbol-1> <Boolean> <GeneratedSymbol-2>
     * GeneratedSymbol-1 := lambda
     * GeneratedSymbol-1 := "!"
     * GeneratedSymbol-2 := lambda
     * GeneratedSymbol-2 := BooleanOperator Boolean GeneratedSymbol-2
     */
    public function testOrderOfConvertedBnfProductions()
    {
        $testGrammars = $this->getTestGrammars();
        $expressionUtils = $testGrammars->getExpressionUtils();

        $productions = array();

        $leftSide = $expressionUtils->createNonTerminal('Expression');
        $expressionItems = [
            new Optional($expressionUtils->createTerminal('!')),
            $expressionUtils->createNonTerminal('Boolean'),
            new Repetition(
                $expressionUtils->createSequenceFromSymbols(
                    array(
                        $expressionUtils->createNonTerminal('BooleanOperator'),
                        $expressionUtils->createNonTerminal('Boolean'),
                    )
                )
            )
        ];
        $rightSide = new Concatenation(array_shift($expressionItems), $expressionItems);
        $productions[] = new StandardProduction($leftSide, $rightSide);
        $ebnfGrammar = new StandardGrammar('simple test boolean logic', $productions);

        $converter = new Conversions();
        $actualBnfProductions = $converter->ebnfToBnf($ebnfGrammar);

        $namingStrategy = $expressionUtils->createNonTerminalNamingStrategy();
        $generatedNames = array(
            $namingStrategy->getName(),
            $namingStrategy->getName()
        );

        $expectedBnfProductions = [
            new StandardProduction(
                $expressionUtils->createNonTerminal('Expression'),
                $expressionUtils->createSequenceFromSymbols(
                    [
                        $expressionUtils->createNonTerminal($generatedNames[0]),
                        $expressionUtils->createNonTerminal('Boolean'),
                        $expressionUtils->createNonTerminal($generatedNames[1]),
                    ]
                )
            ),
            new StandardProduction(
                $expressionUtils->createNonTerminal($generatedNames[0]),
                $expressionUtils->createSequenceFromListOfStringSymbols(array(''))
            ),
            new StandardProduction(
                $expressionUtils->createNonTerminal($generatedNames[0]),
                $expressionUtils->createSequenceFromListOfStringSymbols(array('!'))

            ),
            new StandardProduction(
                $expressionUtils->createNonTerminal($generatedNames[1]),
                $expressionUtils->createSequenceFromListOfStringSymbols(array(''))
            ),
            new StandardProduction(
                $expressionUtils->createNonTerminal($generatedNames[1]),
                $expressionUtils->createSequenceFromSymbols(
                    array(
                        $expressionUtils->createNonTerminal('BooleanOperator'),
                        $expressionUtils->createNonTerminal('Boolean'),
                        $expressionUtils->createNonTerminal($generatedNames[1])
                    )
                )
            )
        ];



        $assertFailMessage = 'Expected a different set of bnf productions';
        $this->assertEquals(count($expectedBnfProductions), count($actualBnfProductions), $assertFailMessage);

        while (!is_null(key($expectedBnfProductions)) && !is_null(key($actualBnfProductions)) ) {
            /** @var $expectedBnfProduction Production */
            $expectedBnfProduction = current($expectedBnfProductions); next($expectedBnfProductions);
            /** @var $actualBnfProduction Production */
            $actualBnfProduction = current($actualBnfProductions); next($actualBnfProductions);

            $this->assertEquals(
                array($expectedBnfProduction->getNonTerminal()->getType(), $expectedBnfProduction->getNonTerminal()->toString()),
                array($actualBnfProduction->getNonTerminal()->getType(), $actualBnfProduction->getNonTerminal()->toString()),
                $assertFailMessage
            );

            $this->assertEquals(
                $expectedBnfProduction->getExpression(),
                $actualBnfProduction->getExpression(),
                $assertFailMessage
            );
        }


    }
}
