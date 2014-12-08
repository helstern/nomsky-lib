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
     * @param Symbol $symbol
     * @return SymbolAdapter
     */
    static public function adapt(Symbol $symbol)
    {
        $expression = new self($symbol->getType(), $symbol->getType());

        return $expression;
    }

    /**
     * @return SymbolAdapter
     */
    static public function createEpsilonAdapter()
    {
        $epsilon = new EpsilonSymbol;
        return new self($epsilon->getType(), $epsilon->hashCode());
    }

    /**
     * @param string $symbol
     * @throws \InvalidArgumentException
     * @return SymbolAdapter
     */
    static public function createTerminal($symbol)
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
