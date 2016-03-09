<?php namespace Helstern\Nomsky\GrammarAnalysis\Algorithms;

use Helstern\Nomsky\Grammar\Symbol\EpsilonSymbol;
use Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolTypeEquals;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\Grammar\Symbol\SymbolSet;
use Helstern\Nomsky\GrammarAnalysis\ParseSets\ParseSets;

/**
 * Calculates the follow set of non-terminals in an  expression
 */
class FollowSetCalculator
{
    /** @var  FirstSetCalculator */
    private $firstSetCalculator;

    public function __construct(FirstSetCalculator $firstSetCalculator)
    {
        $this->firstSetCalculator = $firstSetCalculator;
    }

    /**
     * Lists all occurrences B of a non terminal in the right hand side of a production A -> aBc
     *
     * @param \ArrayObject $existing
     * @param Symbol $lhs
     * @param array|Symbol[] $rhs
     *
     * @return int the number of added occurrences
     */
    public function addNonTerminalOccurrences(\ArrayObject $existing, $lhs, array $rhs)
    {
        $isNonTerminal = SymbolTypeEquals::newInstanceMatchingNonTerminals();

        $added = 0;
        $preceding = [];
        do {
            $symbol = array_shift($rhs);
            if ($isNonTerminal->matchSymbol($symbol)) {
                $occurrence = new SymbolOccurrence($symbol, $preceding, $rhs, $lhs);
                $existing->append($occurrence);
                $added++;
            } else {
                $preceding[] = $symbol;
            }
        } while (count($rhs));

        return $added;
    }

    /**
     * @param \Helstern\Nomsky\Grammar\Symbol\SymbolSet $set
     * @param SymbolOccurrence $occurrence
     * @param \Helstern\Nomsky\GrammarAnalysis\ParseSets\ParseSets $followSets
     * @param \Helstern\Nomsky\GrammarAnalysis\ParseSets\ParseSets $firstSets
     *
     * @return bool
     */
    public function processOccurrence(
        SymbolSet $set,
        SymbolOccurrence $occurrence,
        ParseSets $followSets,
        ParseSets $firstSets
    ) {
        $symbol = $occurrence->getSymbol();
        $isTerminal = SymbolTypeEquals::newInstanceMatchingTerminals();
        if ($isTerminal->matchSymbol($symbol)) {
            return false;
        }

        $following = $occurrence->getFollowing();
        $epsilonAdded = $this->firstSetCalculator->processSymbolList($set, $following, $firstSets);
        if ($epsilonAdded) {
            $set->remove(new EpsilonSymbol());

            $lhs = $occurrence->getProductionNonTerminal();
            $otherSet  = $followSets->getTerminalSet($lhs);
            $set->addAll($otherSet);
        }
        return true;
    }
}
