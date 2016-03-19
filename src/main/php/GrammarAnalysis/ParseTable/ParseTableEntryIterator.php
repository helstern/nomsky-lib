<?php namespace Helstern\Nomsky\GrammarAnalysis\ParseTable;

use ArrayIterator;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\GrammarAnalysis\Production\NormalizedSet;

class ParseTableEntryIterator implements \Iterator
{
    /** @var ArrayIterator  */
    private $innerIterator;

    /**
     * @param ArrayIterator $innerIterator
     */
    public function __construct(ArrayIterator $innerIterator)
    {
        $this->innerIterator = $innerIterator;
    }

    /**
     * @return NormalizedSet|null
     */
    public function current()
    {
        if ($this->innerIterator->valid()) {
            /** @var ParseTableEntry $entry*/
            $entry = $this->innerIterator->current();
            return $entry->getProductionSet();
        }

        return null;
    }

    public function next()
    {
        $this->innerIterator->next();
    }

    /**
     * @return ParseTableKey|null
     */
    public function key()
    {
        if ($this->innerIterator->valid()) {
            /** @var ParseTableEntry $entry*/
            $entry = $this->innerIterator->current();
            $key = new ParseTableKey($entry->getNonTerminal(), $entry->getTerminal());
            return $key;
        }

        return null;
    }

    public function valid()
    {
        return $this->innerIterator->valid();
    }

    public function rewind()
    {
        $this->innerIterator->rewind();
    }

}
