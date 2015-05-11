<?php namespace Helstern\Nomsky\GrammarAnalysis\Sets;

use Helstern\Nomsky\Grammar\Production\Production;
use Helstern\Nomsky\Grammar\Symbol\SymbolSet;

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
            return $entry->getSymbolSet();
        }

        return null;
    }

    public function next()
    {
        $this->innerIterator->next();
    }

    /**
     * @return Production|null
     */
    public function key()
    {
        if ($this->innerIterator->valid()) {
            /** @var LookAheadSetEntry $entry */
            $entry = $this->innerIterator->current();
            return $entry->getProduction();
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
