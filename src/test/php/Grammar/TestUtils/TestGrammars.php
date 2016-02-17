<?php namespace Helstern\Nomsky\Grammar\TestUtils;

use Helstern\Nomsky\Grammar\StandardGrammar;
use Helstern\Nomsky\Grammar\Expressions\Choice;
use Helstern\Nomsky\Grammar\Expressions\Optional;
use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammar\Expressions\Concatenation;
use Helstern\Nomsky\Grammar\Production\StandardProduction;

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
     * @return StandardGrammar
     */
    public function ebnfSimpleTestBooleanLogicGrammar()
    {
        $expressionUtils = $this->getExpressionUtils();

        $productions = array();

        $leftSide = $expressionUtils->createNonTerminal('Expression');
        $expressionItems = array(
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
        );
        $rightSide = new Concatenation(array_shift($expressionItems), $expressionItems);
        $productions[] = new StandardProduction($leftSide, $rightSide);

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
        $rightSide = new Choice(array_shift($expressionItems), $expressionItems);
        $productions[] = new StandardProduction($leftSide, $rightSide);

        $leftSide = $expressionUtils->createNonTerminal('BooleanOperator');
        $expressionItems = array(
            $expressionUtils->createTerminal('And'),
            $expressionUtils->createTerminal('Or'),
        );
        $rightSide = new Choice(array_shift($expressionItems), $expressionItems);
        $productions[] = new StandardProduction($leftSide, $rightSide);

        $leftSide = $expressionUtils->createNonTerminal('BooleanConstant');
        $expressionItems = array(
            $expressionUtils->createTerminal('True'),
            $expressionUtils->createTerminal('False'),
        );
        $rightSide = new Choice(array_shift($expressionItems), $expressionItems);
        $productions[] = new StandardProduction($leftSide, $rightSide);

        $grammar = new StandardGrammar('simple test boolean logic', $productions);
        return $grammar;
    }
}
