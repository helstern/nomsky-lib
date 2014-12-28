<?php namespace Helstern\Nomsky\Grammar\Converters;

use Guzzle\Common\Exception\InvalidArgumentException;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\ExpressionIterable;
use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Sequence;
use Helstern\Nomsky\Grammar\Expressions\SymbolAdapter;

class ExpressionTestUtils
{
    /**
     * @return ExpressionGroupTestUtils
     */
    public function getGroupUtils()
    {
        return new ExpressionGroupTestUtils($this);
    }

    /**
     * @param array $symbols
     * @return Sequence
     */
    public function createSequenceFromSymbols(array $symbols)
    {
        return new Sequence(array_shift($symbols), $symbols);
    }

    /**
     * @param array $symbols
     * @return Alternation
     */
    public function createAlternationFromSymbols(array $symbols)
    {
        return new Alternation(array_shift($symbols), $symbols);
    }

    /**
     * @param array $listOfStringSymbols
     * @return Sequence
     */
    public function createSequenceFromListOfStringSymbols(array $listOfStringSymbols)
    {
        $listOfSymbols = $this->createListOfExpressions($listOfStringSymbols);
        $alternation = new Sequence(array_shift($listOfSymbols), $listOfSymbols);

        return $alternation;
    }

    /**
     * @param array $listOfStringSymbols
     * @return Alternation
     */
    public function createAlternationFromListOfStringSymbols(array $listOfStringSymbols)
    {
        $listOfSymbols = $this->createListOfExpressions($listOfStringSymbols);
        $alternation = new Alternation(array_shift($listOfSymbols), $listOfSymbols);

        return $alternation;
    }

    /**
     * @param array $listOfSymbols
     * @throws \InvalidArgumentException
     * @return array|SymbolAdapter[]|Expression[]
     */
    public function createListOfExpressions(array $listOfSymbols)
    {
        $listOfSymbolObjects = array();
        foreach($listOfSymbols as $stringSymbol) {
            if (is_string($stringSymbol)) {
                $listOfSymbolObjects[] = SymbolAdapter::createAdapterForTerminal($stringSymbol);
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
     * @return SymbolAdapter
     */
    public function createNonTerminal($identifier)
    {
        return SymbolAdapter::createAdapterForNonTerminal($identifier);
    }

    /**
     * @param string $stringSymbol
     * @return SymbolAdapter
     */
    public function createTerminal($stringSymbol)
    {
        if ($stringSymbol === '') {
            return SymbolAdapter::createAdapterForEpsilon();
        }
        return SymbolAdapter::createAdapterForTerminal($stringSymbol);
    }

    /**
     * @param \Helstern\Nomsky\Grammar\Expressions\ExpressionIterable $expression
     * @internal param array|\Helstern\Nomsky\Grammar\Expressions\SymbolAdapter[] $listOfSymbols
     * @return string|null
     */
    public function serializeExpressionIterable(ExpressionIterable $expression)
    {
        $listOfSerializedObjects = array();
        /** @var $symbolObject SymbolAdapter */
        foreach($expression as $symbolObject) {
            if ($symbolObject instanceof SymbolAdapter) {
                $listOfSerializedObjects[] = $symbolObject->hashCode();
            } elseif($symbolObject instanceof ExpressionIterable) {
                $listOfSerializedObjects[] = $this->serializeExpressionIterable($symbolObject);
            } else {
                return null;
            }
        }

        if ($expression instanceof Alternation) {
            $separator = '| ';
        } elseif ($expression instanceof Sequence) {
            $separator = ' ';
        } else {
            $separator = ', ';
        }

        return implode($separator, $listOfSerializedObjects);
    }
}
