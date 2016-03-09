<?php namespace Helstern\Nomsky\GrammarAnalysis\Production;

use Helstern\Nomsky\Grammar\Expressions\Concatenation;
use Helstern\Nomsky\Grammar\Expressions\ExpressionSymbol;
use Helstern\Nomsky\Grammar\Grammar;
use Helstern\Nomsky\Grammar\Production\Production;
use Helstern\Nomsky\Grammar\Symbol\Symbol;

class Normalizer
{
    /**
     * @param \Helstern\Nomsky\Grammar\Grammar $g
     *
     * @return array|NormalizedProduction[]
     * @throws \Exception
     */
    public function normalize(Grammar $g)
    {
        $productions = $g->getProductions();
        $normalizedList = [];
        foreach ($productions as $p) {
            $normalized = $this->normalizeProduction($p);
            $normalizedList[] = $normalized;
        }

        return $normalizedList;
    }

    /**
     * @param array|Production[] $list
     *
     * @return array|NormalizedProduction[]
     * @throws \Exception
     */
    public function normalizeList(array $list)
    {
        $normalizedList = [];
        foreach ($list as $p) {
            $normalized = $this->normalizeProduction($p);
            $normalizedList[] = $normalized;
        }

        return $normalizedList;
    }

    /**
     * @param \Helstern\Nomsky\Grammar\Production\Production $p
     *
     * @return \Helstern\Nomsky\GrammarAnalysis\Production\NormalizedProduction
     * @throws \Exception
     */
    public function normalizeProduction(Production $p)
    {

        $expression = $p->getExpression();
        if ($expression instanceof ExpressionSymbol) {
            $lhs = $p->getNonTerminal();
            $normalized = $this->processExpressionSymbolProduction($lhs, $expression);
            return $normalized;
        }

        if ($expression instanceof Concatenation) {
            $lhs = $p->getNonTerminal();
            $normalized = $this->processConcatenationProduction($lhs, $expression);
            return $normalized;
        }

        throw new \Exception('the production has not been simplified enough');
    }

    /**
     * @param \Helstern\Nomsky\Grammar\Symbol\Symbol $lhs
     * @param \Helstern\Nomsky\Grammar\Expressions\ExpressionSymbol $rhs
     *
     * @return \Helstern\Nomsky\GrammarAnalysis\Production\NormalizedProduction
     */
    private function processExpressionSymbolProduction(Symbol $lhs, ExpressionSymbol $rhs)
    {
        $normalizedRhs = [];
        $normalizedRhs[] = $rhs;
        $normalized = new NormalizedProduction($lhs, $normalizedRhs);
        return $normalized;
    }

    /**
     * @param \Helstern\Nomsky\Grammar\Symbol\Symbol $lhs
     * @param \Helstern\Nomsky\Grammar\Expressions\Concatenation $rhs
     *
     * @return \Helstern\Nomsky\GrammarAnalysis\Production\NormalizedProduction
     * @throws \Exception
     */
    private function processConcatenationProduction(Symbol $lhs, Concatenation $rhs)
    {
        $normalizedRhs = [];
        foreach ($rhs as $childExpression) {
            if ($childExpression instanceof ExpressionSymbol) {
                $normalizedRhs[] = $childExpression;
            } else {
                throw new \Exception('the production has not been simplified enough');
            }
        }
        $normalized = new NormalizedProduction($lhs, $normalizedRhs);
        return $normalized;
    }
}
