<?php namespace Helstern\Nomsky\GrammarAnalysis\ParseSets;

use Helstern\Nomsky\Grammar\Symbol\SymbolSet;
use Helstern\Nomsky\GrammarAnalysis\Production\NormalizedProduction;

class LookAheadSetEntryIterator implements \Iterator
{
    /** @var \ArrayIterator  */
    protected $innerIterator;

    /**
     * @param \ArrayIterator $innerIterator
     */
    public function __construct(\ArrayIterator $innerIterator)
    {
        $this->innerIterator = $innerIterator;
    }

    /**
     * @return SymbolSet|null
     */
    public function current()
    {
        if ($this->innerIterator->valid()) {
            /** @var LookAheadSetEntry $entry */
            $entry = $this->innerIterator->current();
            return $entry->getValue();
        }

        return null;
    }

    public function next()
    {
        $this->innerIterator->next();
    }

    /**
     * @return NormalizedProduction|null
     */
    public function key()
    {
        if ($this->innerIterator->valid()) {
            /** @var LookAheadSetEntry $entry */
            $entry = $this->innerIterator->current();
            return $entry->getKey();
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
