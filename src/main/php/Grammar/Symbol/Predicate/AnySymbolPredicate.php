<?php namespace Helstern\Nomsky\Grammar\Symbol\Predicate;

use Helstern\Nomsky\Grammar\Symbol\Symbol;

class AnySymbolPredicate implements SymbolPredicate
{
    /** @var \Helstern\Nomsky\Grammar\Symbol\Predicate\AnySymbolPredicate */
    static private $singletonInstance;

    /**
     * @return \Helstern\Nomsky\Grammar\Symbol\Predicate\AnySymbolPredicate
     */
    static public function singletonInstance()
    {
        if (is_null(self::$singletonInstance)) {
            self::$singletonInstance = new self;
        }

        return self::$singletonInstance;
    }

    /**
     * @param Symbol $symbol
     * @return bool
     */
    public function matchSymbol(Symbol $symbol)
    {
        return true;
    }
}
