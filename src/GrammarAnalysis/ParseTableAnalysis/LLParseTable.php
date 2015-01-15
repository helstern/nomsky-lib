<?php namespace Helstern\Nomsky\GrammarAnalysis\ParseTableAnalysis;

use Helstern\Nomsky\Grammar\Production\Set\SetEntry as ProductionSetEntry;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\Grammar\Symbol\SymbolSet;

class LLParseTable
{
    /** @var SymbolSet|Symbol[] */
    protected $nonTerminals;

    /** @var SymbolSet|Symbol[] */
    protected $terminals;

    /** @var array */
    protected $entriesByTerminal;

    /** @var array  */
    protected $entriesByNonTerminal;

    /** @var array|ProductionSetEntry[] $entry */
    protected $uniqueEntries;

    /**
     * @param SymbolSet $nonTerminalsList
     * @param SymbolSet $terminalsList
     */
    public function __construct(SymbolSet $nonTerminalsList, SymbolSet $terminalsList)
    {
        $this->nonTerminals = $nonTerminalsList;
        $this->terminals    = $terminalsList;
    }

    /**
     * @param Symbol $symbol
     * @return array|ProductionSetEntry[]
     */
    public function getAllEntriesFor(Symbol $symbol)
    {
        if (array_key_exists($symbol->toString(), $this->entriesByTerminal)) {
            return array_intersect_key($this->uniqueEntries, array_flip($this->entriesByTerminal));
        }

        if (array_key_exists($symbol->toString(), $this->entriesByNonTerminal)) {
            return array_intersect_key($this->uniqueEntries, array_flip($this->entriesByNonTerminal));
        }

        return array();
    }

    /**
     * @param Symbol $nonTerminal
     * @param Symbol $terminal
     * @return array|ProductionSetEntry[]
     */
    public function getEntry(Symbol $nonTerminal, Symbol $terminal)
    {
        if (
            array_key_exists($nonTerminal->toString(), $this->entriesByNonTerminal) &&
            array_key_exists($terminal->toString(), $this->entriesByTerminal)
        ) {
            return array_intersect_key(
                $this->uniqueEntries,
                array_flip(
                    array_intersect($this->entriesByNonTerminal, $this->entriesByTerminal)
                )
            );
        }

        return array();
    }

    /**
     * @param ProductionSetEntry $entry
     * @param SymbolSet $terminalSet
     * @return bool
     */
    public function addAllEntries(ProductionSetEntry $entry, SymbolSet $terminalSet)
    {
        $allAdded = true;
        foreach ($terminalSet as $terminal) {
            $allAdded &= $this->addEntry($entry, $terminal);
        }

        return $allAdded;
    }

    /**
     * @param ProductionSetEntry $entry
     * @param Symbol $terminal
     * @throws \RuntimeException
     * @return bool
     */
    public function addEntry(ProductionSetEntry $entry, Symbol $terminal)
    {
        $nonTerminal = $entry->getProduction()->getNonTerminal();
        $this->assertKnownSymbols($nonTerminal, $terminal, new \RuntimeException('Uknown symbols'));

        $this->addProductionSetEntry($entry);

        $hashCode = (string )$entry->getHashKey()->toString();
        $added = $this->addColumnAndRowEntry($nonTerminal, $terminal, $hashCode);

        return $added;
    }

    /**
     * @param Symbol $nonTerminal
     * @param Symbol $terminal
     * @param \Exception $e
     * @throws \Exception
     * @return null
     */
    protected function assertKnownSymbols(Symbol $nonTerminal, Symbol $terminal, \Exception $e)
    {
        if ($this->nonTerminals->contains($nonTerminal) && $this->terminals->contains($terminal)) {
            return null;
        }

        throw $e;
    }

    /**
     * @param Symbol $nonTerminal
     * @param Symbol $terminal
     * @param string $hashCode
     * @return bool
     */
    protected function addColumnAndRowEntry(Symbol $nonTerminal, Symbol $terminal, $hashCode)
    {
        /** @var bool $exists */
        $exists = 1;

        if (! array_key_exists($nonTerminal->toString(), $this->entriesByNonTerminal)) {
            $this->entriesByNonTerminal[$nonTerminal->toString()] = array($hashCode);
        } elseif (! in_array($nonTerminal->toString(), $this->entriesByNonTerminal)) {
            $this->entriesByNonTerminal[$nonTerminal->toString()][] = $hashCode;
        } else {
            $exists <<= 1;
        }

        if (! array_key_exists($terminal->toString(), $this->entriesByTerminal)) {
            $this->entriesByTerminal[$terminal->toString()] = array($hashCode);
        } elseif (! in_array($terminal->toString(), $this->entriesByTerminal)) {
            $this->entriesByTerminal[$terminal->toString()][] = $hashCode;
        } else {
            $exists <<= 1;
        }

        return $exists < 4;
    }

    protected function addProductionSetEntry(ProductionSetEntry $entry)
    {
        $hashCode = $entry->getHashKey()->toString();
        if (array_key_exists($hashCode, $this->uniqueEntries)) {
            return false;
        } else {
            $this->uniqueEntries[$hashCode] = $entry;
            return true;
        }
    }
}
