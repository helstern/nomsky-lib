<?php namespace Helstern\Nomsky\Grammar\Symbol\Selector;

use Helstern\Nomsky\Grammar\Symbol\Symbol;

class FirstTerminalSelector implements Selector
{
    /** @var boolean */
    protected $matchedOne = false;

    /**
     * @return FirstTerminalSelector
     */
    public static function newInstance()
    {
        return new self;
    }
    /**
     * @param Symbol $symbol
     * @return bool
     */
    public function match(Symbol $symbol)
    {
        $match = $symbol->getType() === Symbol::TYPE_TERMINAL;
        $this->matchedOne |= $match;

        return $match;
    }

    /**
     * @return boolean
     */
    public function continueSelecting()
    {
        return $this->matchedOne;
    }
}
