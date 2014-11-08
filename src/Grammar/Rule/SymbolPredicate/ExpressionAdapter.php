<?php namespace Helstern\Nomsky\Grammar\Rule\SymbolPredicate;

use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolPredicate;
use Helstern\Nomsky\Grammar\Symbol\Symbol;

class ExpressionAdapter implements SymbolPredicate
{
    /** @var SymbolPredicate */
    protected $symbolPredicate;

    /**
     * @param SymbolPredicate $symbolPredicate
     */
    public function __construct(SymbolPredicate $symbolPredicate)
    {
        $this->symbolPredicate = $symbolPredicate;
    }

    /**
     * @return SymbolPredicate
     */
    public function getPredicate()
    {
        return $this->symbolPredicate;
    }

    /**
     * @param Expression $expression
     * @return bool
     */
    public function matchExpression(Expression $expression)
    {
        if ($expression instanceof Symbol) {
            return $this->matchSymbol($expression);
        }

        return false;
    }

    /**
     * @param Symbol $symbol
     * @return mixed
     */
    public function matchSymbol(Symbol $symbol)
    {
        return $this->symbolPredicate->matchSymbol($symbol);
    }
}
