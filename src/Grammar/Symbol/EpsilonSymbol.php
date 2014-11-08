<?php namespace Helstern\Nomsky\Grammar\Symbol;

class EpsilonSymbol implements Symbol
{
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
    public function hashCode()
    {
        return '0';
    }
}
