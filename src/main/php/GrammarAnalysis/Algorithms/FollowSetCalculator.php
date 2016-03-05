<?php namespace Helstern\Nomsky\GrammarAnalysis\Algorithms;

use Helstern\Nomsky\Grammar\Expressions\Concatenation;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\ExpressionSymbol;
use Helstern\Nomsky\Grammar\Production\StandardProduction;
use Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolTypeEquals;
use Helstern\Nomsky\Grammar\Symbol\Symbol;

/**
 * Calculates the follow set of non-terminals in an  expression
 */
class FollowSetCalculator
{
    /**
     * For each non terminal in $expression get the rest of symbols following it
     *
     * @param Concatenation $expression
     *
     * @return array
     */
    public function processConcatenation(Concatenation $expression)
    {
        $isNonTerminal = SymbolTypeEquals::newInstanceMatchingNonTerminals();
        $listOfExpressions = $expression->toArray();

        $listOfProductions = [];
        do {
            $symbolOrExpression = array_shift($listOfExpressions);
            if ($symbolOrExpression instanceof Symbol && $isNonTerminal->matchSymbol($symbolOrExpression)) {
                $production = $this->createProduction($symbolOrExpression, $listOfExpressions);
                $listOfProductions[] = $production;
            }
        } while (count($listOfExpressions));

        return $listOfProductions;
    }

    /**
     * Returns a one element array
     *
     * @param Expression $expression
     *
     * @return array
     */
    public function processExpressionAsList(Expression $expression)
    {
        $production = $this->processExpression($expression);
        if (!is_null($production)) {
            return [$production];
        }

        return [];
    }

    /**
     *
     * @param Expression $expression
     *
     * @return StandardProduction|null
     */
    public function processExpression(Expression $expression)
    {
        $isNonTerminal = SymbolTypeEquals::newInstanceMatchingNonTerminals();
        if ($expression instanceof Symbol && $isNonTerminal->matchSymbol($expression)) {
            $production = new StandardProduction($expression, ExpressionSymbol::createAdapterForEpsilon());
            return $production;
        }
        return null;
    }

    /**
     * @param \Helstern\Nomsky\Grammar\Symbol\Symbol $lhs
     * @param array $rhs
     *
     * @return StandardProduction
     */
    private function createProduction(Symbol $lhs, array $rhs)
    {
        $remaining = count($rhs);

        if (0 == $remaining) {
            $production = new StandardProduction($lhs, ExpressionSymbol::createAdapterForEpsilon());
        } elseif (1 == $remaining) {
            $production = new StandardProduction($lhs, $rhs[0]);
        } else {
            $production = new StandardProduction($lhs, new Concatenation($rhs[0], array_slice($rhs, 1)));
        }

        return $production;
    }
}
