<?php namespace Helstern\Nomsky\Grammar\TestUtils;

use Helstern\Nomsky\Grammar\DefaultGrammar;
use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\OptionalItem;
use Helstern\Nomsky\Grammar\Expressions\OptionalList;
use Helstern\Nomsky\Grammar\Expressions\Sequence;
use Helstern\Nomsky\Grammar\Production\DefaultProduction;

class TestGrammars
{
    /** @var ExpressionUtils */
    protected $expressionTestUtils;

    /**
     * @return ExpressionUtils
     */
    public function getExpressionUtils()
    {
        if (is_null($this->expressionTestUtils)) {
            $this->expressionTestUtils = new ExpressionUtils();
        }

        return $this->expressionTestUtils;
    }

    /**
     *
     *   Expression      := [ "!" ] <Boolean> { BooleanOperator Boolean }
     *   Boolean         := BooleanConstant | Expression | "(" <Expression> ")"
     *   BooleanOperator := "And" | "Or"
     *   BooleanConstant := "True" | "False"
     *
     * @return DefaultGrammar
     */
    public function ebnfSimpleTestBooleanLogicGrammar()
    {
        $expressionUtils = $this->getExpressionUtils();

        $productions = array();

        $leftSide = $expressionUtils->createNonTerminal('Expression');
        $expressionItems = array(
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
        );
        $rightSide = new Sequence(array_shift($expressionItems), $expressionItems);
        $productions[] = new DefaultProduction($leftSide, $rightSide);

        $leftSide = $expressionUtils->createNonTerminal('Boolean');
        $expressionItems = array(
            $expressionUtils->createNonTerminal('BooleanConstant'),
            $expressionUtils->createNonTerminal('Expression'),
            $expressionUtils->createSequenceFromSymbols(
                 array(
                     $expressionUtils->createTerminal('('),
                     $expressionUtils->createNonTerminal('Expression'),
                     $expressionUtils->createTerminal(')')
                 )
            )
        );
        $rightSide = new Alternation(array_shift($expressionItems), $expressionItems);
        $productions[] = new DefaultProduction($leftSide, $rightSide);

        $leftSide = $expressionUtils->createNonTerminal('BooleanOperator');
        $expressionItems = array(
            $expressionUtils->createTerminal('And'),
            $expressionUtils->createTerminal('Or'),
        );
        $rightSide = new Alternation(array_shift($expressionItems), $expressionItems);
        $productions[] = new DefaultProduction($leftSide, $rightSide);

        $leftSide = $expressionUtils->createNonTerminal('BooleanConstant');
        $expressionItems = array(
            $expressionUtils->createTerminal('True'),
            $expressionUtils->createTerminal('False'),
        );
        $rightSide = new Alternation(array_shift($expressionItems), $expressionItems);
        $productions[] = new DefaultProduction($leftSide, $rightSide);

        $grammar = new DefaultGrammar('simple test boolean logic', $productions);
        return $grammar;
    }
}
