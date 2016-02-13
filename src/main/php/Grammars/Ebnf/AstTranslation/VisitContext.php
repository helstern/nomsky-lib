<?php namespace Helstern\Nomsky\Grammars\Ebnf\AstTranslation;

use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Production\Production;
use Helstern\Nomsky\Grammar\Symbol\Symbol;

class VisitContext
{
    public function pushMarker($marker)
    {

    }

    public function pushExpression(Expression $expression)
    {

    }

    /**
     * @param $marker
     * @return Expression[]
     */
    public function popExpressions($marker)
    {

    }

    /**
     * @param mixed $marker
     * @return Expression
     */
    public function popOneExpression($marker = null)
    {

    }

    public function pushSymbol(Symbol $symbol, $marker)
    {

    }

    /**
     * @param mixed $marker
     * @return Symbol
     */
    public function popSymbol($marker)
    {

    }

    public function addProduction(Production $production)
    {

    }
}
