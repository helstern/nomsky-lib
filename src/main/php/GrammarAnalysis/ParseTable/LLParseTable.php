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
    private $uniqueEntries = [];

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
        $this->assertKnownSymbols($nonTerminal, $terminal);
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
     * @throws \Exception
     * @return null
     */
    private function assertKnownSymbols(Symbol $nonTerminal, Symbol $terminal)
    {
        $knownNonTerminal = $this->nonTerminals->contains($nonTerminal);
        $knownTerminal = $this->terminals->contains($terminal);

        if ($knownNonTerminal && $knownTerminal) {
            return true;
        }

        if (! $knownNonTerminal && ! $knownTerminal) {
            $assertionMessage = 'Uknown symbols: non-terminal %s and terminal %s';
            $assertionMessage = sprintf($assertionMessage, $nonTerminal->toString(), $terminal->toString());
        } else if ($knownTerminal) {
            $assertionMessage = 'Uknown non-terminal: %s';
            $assertionMessage = sprintf($assertionMessage, $nonTerminal->toString());
        }  else {
            $assertionMessage = 'Uknown terminal: %s';
            $assertionMessage = sprintf($assertionMessage, $terminal->toString());
        }

        throw new \RuntimeException($assertionMessage);
    }
}
