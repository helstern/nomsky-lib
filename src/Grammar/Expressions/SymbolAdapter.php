<?php namespace Helstern\Nomsky\Grammar\Expressions;

use Helstern\Nomsky\Grammar\Symbol\EpsilonSymbol;
use Helstern\Nomsky\Grammar\Symbol\Symbol;

class SymbolAdapter implements Expression, Symbol
{
    /** @var int */
    protected $type;

    /** @var string */
    protected $symbol;

    /**
     * @return SymbolAdapter
     */
    static public function createAdapterForEpsilon()
    {
        $epsilon = new EpsilonSymbol;
        return new self($epsilon->getType(), $epsilon->hashCode());
    }

    /**
     * @param Symbol $symbol
     * @return SymbolAdapter
     */
    static public function createAdapterForSymbol(Symbol $symbol)
    {
        $expression = new self($symbol->getType(), $symbol->hashCode());

        return $expression;
    }

    /**
     * @param $identifier
     * @throws \InvalidArgumentException
     * @return SymbolAdapter
     */
    static public function createAdapterForNonTerminal($identifier)
    {
        if (! is_string($identifier)) {
            throw new \InvalidArgumentException(sprintf('%s requires a string', __METHOD__));
        }
        return new self(Symbol::TYPE_NON_TERMINAL, $identifier);
    }

    /**
     * @param string $symbol
     * @throws \InvalidArgumentException
     * @return SymbolAdapter
     */
    static public function createAdapterForTerminal($symbol)
    {
        if (! is_string($symbol)) {
            throw new \InvalidArgumentException(sprintf('%s requires a string', __METHOD__));
        }
        return new self(Symbol::TYPE_TERMINAL, $symbol);
    }

    /**
     * @param int $type
     * @param string $symbol
     */
    public function __construct($type, $symbol)
    {
        $this->type     = $type;
        $this->symbol   = $symbol;
    }
    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function hashCode()
    {
        return $this->symbol;
    }
}
