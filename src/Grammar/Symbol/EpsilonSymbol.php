<?php namespace Helstern\Nomsky\Grammar\Symbol;

class EpsilonSymbol implements Symbol
{
    /** @var EpsilonSymbol */
    private static $singletonInstance;

    /**
     * @return EpsilonSymbol
     */
    public static function singletonInstance()
    {
        if (is_null(self::$singletonInstance)) {
            self::$singletonInstance = new self;
        }

        return self::$singletonInstance;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return Symbol::TYPE_TERMINAL;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return 'ε';
    }
}
