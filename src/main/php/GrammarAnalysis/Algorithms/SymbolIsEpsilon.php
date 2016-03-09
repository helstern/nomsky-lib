<?php namespace Helstern\Nomsky\GrammarAnalysis\Algorithms;

use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\Grammar\Symbol\EpsilonSymbol;

use Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolPredicate;

class SymbolIsEpsilon implements SymbolPredicate
{
    /** @var SymbolIsEpsilon */
    private static $singletonInstance;

    /**
     * @return SymbolIsEpsilon
     */
    public static function singletonInstance()
    {
        if (is_null(self::$singletonInstance)) {
            self::$singletonInstance = new self;
        }

        return self::$singletonInstance;
    }

    public function matchSymbol(Symbol $symbol)
    {
        return ($symbol instanceof EpsilonSymbol);
    }
}
