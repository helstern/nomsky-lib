<?php namespace Helstern\Nomsky\GrammarAnalysis\ParseSets;

use Helstern\Nomsky\Grammar\Expressions\Concatenation;
use Helstern\Nomsky\Grammar\Production\Production;
use Helstern\Nomsky\Grammar\Symbol\ArraySet;
use Helstern\Nomsky\Grammar\Symbol\SymbolSet;
use Helstern\Nomsky\Grammar\Symbol\Symbol;

use Helstern\Nomsky\GrammarAnalysis\Algorithms\FirstSetCalculator;

/**
 * This class generates all the first sets of a grammar
 * The first set of a right-hand-side alpha is the set of all terminals that begin the strings derivable from alpha
 *
 * The first sets are used to produce the predict set of grammar and the ll parse table
 */
class FirstSetFiller
{
    /** @var FirstSetCalculator */
    private $stringFirstSet;

    /**
     * @param FirstSetCalculator $stringFirstSet
     */
    public function __construct(FirstSetCalculator $stringFirstSet)
    {
        $this->stringFirstSet = $stringFirstSet;
    }

    /**
     * @param ParseSets $firstSets
     * @param SymbolSet $derivesEpsilon
     * @param array $nonTerminals
     *
     * @return int
     * @throws \Exception
     */
    public function addEpsilonNonTerminals(ParseSets $firstSets, SymbolSet $derivesEpsilon, array $nonTerminals)
    {
        $count = 0;
        foreach($nonTerminals as $nonTerminal) {
            if ($derivesEpsilon->contains($nonTerminal)) {
                $count++;
                $firstSets->addEpsilon($nonTerminal);
            }
        }

        return $count;
    }

    /**
     * @param ParseSets $firstSets
     * @param array|Production[] $list
     *
     * @return bool
     */
    public function addProductionList(ParseSets $firstSets, array $list)
    {
        //initialize the obvious first sets of productions which start with a terminal
        foreach ($list as $production) {
            $symbol = $production->getFirstSymbol();
            if ($symbol->getType() == Symbol::TYPE_TERMINAL) {
                $firstSets->addTerminal($production->getNonTerminal(), $symbol);
            }
        }

        //initialize first sets for productions which contain several non-terminals
        do {
            $changes = false;
            foreach ($list as $production) {

                $updateSet = new ArraySet();
                $expression =  $production->getExpression();
                if ($expression instanceof Concatenation) {
                    $this->stringFirstSet->processConcatenation($updateSet, $expression, $firstSets);
                } else {
                    $this->stringFirstSet->processExpression($updateSet, $expression, $firstSets);
                }

                $nonTerminal = $production->getNonTerminal();
                $changes |= $firstSets->addAllTerminals($nonTerminal, $updateSet);

            }
        } while($changes);

        return true;
    }
}
