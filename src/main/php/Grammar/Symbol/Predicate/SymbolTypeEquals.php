<?php namespace Helstern\Nomsky\Grammar\Symbol\Predicate;

use Helstern\Nomsky\Grammar\Symbol\Symbol;

class SymbolTypeEquals implements SymbolPredicate
{
    /** @var string */
    protected $type;

    /**
     * @return SymbolTypeEquals
     */
    static public function newInstanceMatchingNonTerminals()
    {
        $instance = self::newInstance(Symbol::TYPE_TERMINAL);
        return $instance;
    }

    /**
     * @return SymbolTypeEquals
     */
    static public function newInstanceMatchingTerminals()
    {
        $instance = self::newInstance(Symbol::TYPE_TERMINAL);
        return $instance;
    }

    /**
     * @param $symbolType
     * @return SymbolTypeEquals
     */
    static public function newInstance($symbolType)
    {
        return new static($symbolType);
    }

    /**
     * @param Symbol $symbol
     * @return bool
     */
    static public function isNonTerminal(Symbol $symbol)
    {
        $instance = self::newInstance(Symbol::TYPE_NON_TERMINAL);
        return $instance->matchSymbol($symbol);
    }

    /**
     * @param Symbol $symbol
     * @return bool
     */
    static public function isTerminal(Symbol $symbol)
    {
        $instance = self::newInstance(Symbol::TYPE_TERMINAL);
        return $instance->matchSymbol($symbol);
    }

    /**
     * @param $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @param Symbol $symbol
     * @return bool
     */
    public function matchSymbol(Symbol $symbol)
    {
        return $symbol->getType() === $this->type;
    }
}
