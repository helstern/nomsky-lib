<?php namespace Helstern\Nomsky\Grammar\TestUtils;

use Helstern\Nomsky\Grammar\Transformations\EliminateOptionals\IncrementalNamingStrategy;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\ExpressionIterable;
use Helstern\Nomsky\Grammar\Expressions\Choice;
use Helstern\Nomsky\Grammar\Expressions\Concatenation;
use Helstern\Nomsky\Grammar\Expressions\ExpressionSymbol;

class ExpressionUtils
{
    /**
     * @return IncrementalNamingStrategy
     */
    public function createNonTerminalNamingStrategy()
    {
        return new IncrementalNamingStrategy();
    }

    /**
     * @return ExpressionGroupUtils
     */
    public function getGroupUtils()
    {
        return new ExpressionGroupUtils($this);
    }

    /**
     * @param array $symbols
     *
*@return Concatenation
     */
    public function createSequenceFromSymbols(array $symbols)
    {
        return new Concatenation(array_shift($symbols), $symbols);
    }

    /**
     * @param array $symbols
     *
*@return Choice
     */
    public function createAlternationFromSymbols(array $symbols)
    {
        return new Choice(array_shift($symbols), $symbols);
    }

    /**
     * @param array $listOfStringSymbols
     *
*@return Concatenation
     */
    public function createSequenceFromListOfStringSymbols(array $listOfStringSymbols)
    {
        $listOfSymbols = $this->createListOfExpressions($listOfStringSymbols);
        $alternation = new Concatenation(array_shift($listOfSymbols), $listOfSymbols);

        return $alternation;
    }

    /**
     * @param array $listOfStringSymbols
     *
*@return Choice
     */
    public function createAlternationFromListOfStringSymbols(array $listOfStringSymbols)
    {
        $listOfSymbols = $this->createListOfExpressions($listOfStringSymbols);
        $alternation = new Choice(array_shift($listOfSymbols), $listOfSymbols);

        return $alternation;
    }

    /**
     * @param array $listOfSymbols
     * @throws \InvalidArgumentException
     * @return array|ExpressionSymbol[]|Expression[]
     */
    public function createListOfExpressions(array $listOfSymbols)
    {
        $listOfSymbolObjects = array();
        foreach($listOfSymbols as $stringSymbol) {
            if (is_string($stringSymbol)) {
                $listOfSymbolObjects[] = $this->createTerminal($stringSymbol);
            } elseif ($stringSymbol instanceof Expression) {
                $listOfSymbolObjects[] = $stringSymbol;
            } else {
                throw new \InvalidArgumentException(sprintf('unknown object type %s', get_class($stringSymbol)));
            }
        }

        return $listOfSymbolObjects;
    }

    /**
     * @param string $identifier
     * @return ExpressionSymbol
     */
    public function createNonTerminal($identifier)
    {
        return ExpressionSymbol::createAdapterForNonTerminal($identifier);
    }

    /**
     * @param string $stringSymbol
     * @return ExpressionSymbol
     */
    public function createTerminal($stringSymbol)
    {
        if ($stringSymbol === '') {
            return ExpressionSymbol::createAdapterForEpsilon();
        }
        return ExpressionSymbol::createAdapterForTerminal($stringSymbol);
    }

    /**
     * @param \Helstern\Nomsky\Grammar\Expressions\ExpressionIterable $expression
     * @internal param array|\Helstern\Nomsky\Grammar\Expressions\SymbolAdapter[] $listOfSymbols
     * @return string|null
     */
    public function serializeExpressionIterable(ExpressionIterable $expression)
    {
        $listOfSerializedObjects = array();
        /** @var $symbolObject ExpressionSymbol */
        foreach($expression as $symbolObject) {
            if ($symbolObject instanceof ExpressionSymbol) {
                $listOfSerializedObjects[] = $symbolObject->toString();
            } elseif($symbolObject instanceof ExpressionIterable) {
                /** @var $symbolObject ExpressionIterable */
                $listOfSerializedObjects[] = $this->serializeExpressionIterable($symbolObject);
            } else {
                return null;
            }
        }

        if ($expression instanceof Choice) {
            $separator = '| ';
        } elseif ($expression instanceof Concatenation) {
            $separator = ' ';
        } else {
            $separator = ', ';
        }

        return implode($separator, $listOfSerializedObjects);
    }
}
