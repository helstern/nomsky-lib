<?php namespace Helstern\Nomsky\Grammars\Ebnf\Grammar;

use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Production\Production;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use SebastianBergmann\RecursionContext\Exception;

class AstTranslatorContext
{
    /**
     * @var array the stack that holds the left hand symbols
     */
    private $leftHandSymbols = [];

    private $expressions = [];

    private $productionsList = [];

    public function pushExpressionMarker($marker)
    {
        array_push($this->expressions, $marker);
        return $this;
    }

    /**
     * @param Expression $expression
     *
     * @return AstTranslatorContext
     */
    public function pushExpression(Expression $expression)
    {
        array_push($this->expressions, $expression);
        return $this;
    }

    /**
     * @param $marker
     *
     * @return Expression[]
     * @throws \Exception
     */
    public function popExpressions($marker)
    {
        if (empty($this->expressions)) {
            throw new \Exception('the expressions queue is empty');
        }

        $list = [];

        do {
            $expression = array_pop($this->expressions);

            if ($expression === $marker) {
                break;
            }
            $list[] = $expression;

        } while (!is_null(key($this->expressions)));

        if ($expression !== $marker) {
            throw new \Exception('marker not found');
        }

        $list = array_reverse($list);
        return $list;
    }

    /**
     * @param mixed $marker
     * @return Expression
     */
    public function popOneExpression($marker)
    {
        $expression = array_pop($this->expressions);
        if (! $expression instanceof Expression) {
            array_push($this->leftHandSymbols, $symbol);
            return null;
        }

        $actualMarker = array_pop($this->expressions);
        if ($actualMarker !== $marker) {
            array_push($this->expressions, $actualMarker);
            array_push($this->expressions, $expression);
            return null;
        }

        return $expression;
    }

    /**
     * @param Symbol $symbol
     * @param $marker
     *
     * @return AstTranslatorContext
     */
    public function pushLeftHandSymbol(Symbol $symbol, $marker)
    {
        array_push($this->leftHandSymbols, $marker);
        array_push($this->leftHandSymbols, $symbol);

        return $this;
    }

    /**
     * @param mixed $marker
     * @return Symbol
     */
    public function popLeftHandSymbol($marker)
    {
        $symbol = array_pop($this->leftHandSymbols);
        if (! $symbol instanceof Symbol) {
            array_push($this->leftHandSymbols, $symbol);
            return null;
        }

        $actualMarker = array_pop($this->leftHandSymbols);
        if ($actualMarker !== $marker) {
            array_push($this->leftHandSymbols, $actualMarker);
            array_push($this->leftHandSymbols, $symbol);
            return null;
        }

        return $symbol;
    }

    /**
     * @param Production $production
     *
     * @return AstTranslatorContext
     */
    public function collectProduction(Production $production)
    {
        array_push($this->productionsList, $production);
        return $this;
    }

    /**
     * @return array|Production[]
     */
    public function getProductions()
    {
        return $this->productionsList;
    }
}
