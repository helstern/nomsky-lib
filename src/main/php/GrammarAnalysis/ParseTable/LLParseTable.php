<?php namespace Helstern\Nomsky\GrammarAnalysis\ParseTable;

use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\Grammar\Symbol\SymbolSet;
use Helstern\Nomsky\GrammarAnalysis\Production\ArraySet;
use Helstern\Nomsky\GrammarAnalysis\Production\HashKeyFactory;
use Helstern\Nomsky\GrammarAnalysis\Production\NormalizedProduction;

class LLParseTable
{
    /** @var SymbolSet|Symbol[] */
    private $nonTerminals;

    /** @var SymbolSet|Symbol[] */
    private $terminals;

    /** @var array|ParseTableEntry[] $entry */
    private $uniqueEntries;

    /** @var HashKeyFactory */
    private $productionHash;

    /**
     * @param SymbolSet $nonTerminalsList
     * @param SymbolSet $terminalsList
     * @param HashKeyFactory $productionHash
     */
    public function __construct(SymbolSet $nonTerminalsList, SymbolSet $terminalsList, HashKeyFactory $productionHash)
    {
        $this->nonTerminals = $nonTerminalsList;
        $this->terminals    = $terminalsList;
        $this->productionHash = $productionHash;
    }

    /**
     * @param Symbol $symbol
     * @return array|NormalizedProduction[]
     */
    public function getAllEntriesFor(Symbol $symbol)
    {
        $set = new ArraySet($this->productionHash);
        foreach ($this->uniqueEntries as $entry) {
            if (
                $entry->getNonTerminal()->toString() == $symbol->toString()
                || $entry->getTerminal()->toString() == $symbol->toString()
            ) {
                $otherSet = $entry->getProductionSet();
                $set->addAll($otherSet);
            }

        }

        return $set->toList();
    }

    /**
     * @param Symbol $nonTerminal
     * @param Symbol $terminal
     * @return array|NormalizedProduction[]
     */
    public function getEntry(Symbol $nonTerminal, Symbol $terminal)
    {
        $entryHash = $this->getEntryHash($nonTerminal, $terminal);
        if (array_key_exists($entryHash, $this->uniqueEntries)) {
            $entry = $this->uniqueEntries[$entryHash];
            return $entry->getProductionSet()->toList();
        }

        return array();
    }

    /**
     * @param \Helstern\Nomsky\Grammar\Symbol\Symbol $nonTerminal
     * @param \Helstern\Nomsky\Grammar\Symbol\Symbol $terminal
     *
     * @return string
     */
    private function getEntryHash(Symbol $nonTerminal, Symbol $terminal)
    {
        $entryHash = $nonTerminal->toString() . ' ' . $terminal->toString();
        return $entryHash;
    }

    public function add(Symbol $nonTerminal, Symbol $terminal, NormalizedProduction $production)
    {
        $this->assertKnownSymbols($nonTerminal, $terminal, new \RuntimeException('Uknown symbols'));
        $entryHash = $this->getEntryHash($nonTerminal, $terminal);

        if (array_key_exists($entryHash, $this->uniqueEntries)) {
            $entry = $this->uniqueEntries[$entryHash];
            return $entry->getProductionSet()->add($production);
        }

        $set = new ArraySet($this->productionHash);
        $set->add($production);
        $entry = new ParseTableEntry($nonTerminal, $terminal, $set);
        $this->uniqueEntries[$entryHash] = $entry;
        return true;
    }

    /**
     * @param Symbol $nonTerminal
     * @param Symbol $terminal
     * @param \Exception $e
     * @throws \Exception
     * @return null
     */
    private function assertKnownSymbols(Symbol $nonTerminal, Symbol $terminal, \Exception $e)
    {
        if ($this->nonTerminals->contains($nonTerminal) && $this->terminals->contains($terminal)) {
            return null;
        }

        throw $e;
    }

}
