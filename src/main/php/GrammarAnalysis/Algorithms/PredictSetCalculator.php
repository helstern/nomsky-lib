<?php namespace Helstern\Nomsky\GrammarAnalysis\Algorithms;

use Helstern\Nomsky\Grammar\Symbol\SymbolSet;
use Helstern\Nomsky\GrammarAnalysis\ParseSets\ParseSets;
use Helstern\Nomsky\GrammarAnalysis\Production\NormalizedProduction;

/**
 * Calculates the follow set of non-terminals in an  expression
 */
class PredictSetCalculator
{
    /** @var  FirstSetCalculator */
    private $firstSetCalculator;

    public function __construct(FirstSetCalculator $firstSetCalculator)
    {
        $this->firstSetCalculator = $firstSetCalculator;
    }

    /**
     * @param \Helstern\Nomsky\Grammar\Symbol\SymbolSet $set
     * @param NormalizedProduction $production
     * @param \Helstern\Nomsky\GrammarAnalysis\ParseSets\ParseSets $followSets
     * @param \Helstern\Nomsky\GrammarAnalysis\ParseSets\ParseSets $firstSets
     *
     * @return bool
     */
    public function processProduction(
        SymbolSet $set,
        NormalizedProduction $production,
        ParseSets $followSets,
        ParseSets $firstSets
    ) {
        $rhs = $production->getRightHandSide();
        $epsilonAdded = $this->firstSetCalculator->processSymbolList($set, $rhs, $firstSets);

        if ($epsilonAdded) {
            $lhs = $production->getLeftHandSide();
            $otherSet  = $followSets->getTerminalSet($lhs);
            $set->addAll($otherSet);
        }

        return $epsilonAdded;
    }
}
