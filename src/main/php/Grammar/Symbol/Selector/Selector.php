<?php namespace Helstern\Nomsky\Grammar\Symbol\Selector;

use Helstern\Nomsky\Grammar\Symbol\Symbol;

interface Selector
{
    /**
     * @param Symbol $symbol
     * @return boolean
     */
    public function match(Symbol $symbol);

    /**
     * @return boolean
     */
    public function continueSelecting();
}
