<?php namespace Helstern\Nomsky\GrammarAnalysis\FirstSetsAnalysis;

use Helstern\Nomsky\Grammar\Grammar;
use Helstern\Nomsky\Grammar\Expressions\ExpressionIterable;

use Helstern\Nomsky\Grammar\Symbol\SymbolSet;
use Helstern\Nomsky\Grammar\Symbol\Symbol;

use Helstern\Nomsky\GrammarAnalysis\Algorithms\ArbitraryStringFirstSet;
use Helstern\Nomsky\GrammarAnalysis\Sets\PredictiveParsingSets;

class SetGenerator
{
    /** @var SymbolSet|Symbol[] */
    protected $derivesEpsilon;

    /** @var ArbitraryStringFirstSet */
    protected $stringFirstSet;

    /**
     * @param SymbolSet|Symbol[] $derivesEpsilon
     * @param ArbitraryStringFirstSet $stringFirstSet
     */
    public function __construct(SymbolSet $derivesEpsilon, ArbitraryStringFirstSet $stringFirstSet)
    {
        $this->derivesEpsilon = $derivesEpsilon;
        $this->stringFirstSet = $stringFirstSet;
    }

    /**
     * @param Grammar $g
     * @return PredictiveParsingSets
     */
    public function generate(Grammar $g)
    {
        $firstSets = $this->createSets($g);

        $nonTerminals = $g->getNonTerminals();
        //initialize the first sets of the non terminals which generate epsilon

        foreach($nonTerminals as $nonTerminal) {
            if ($this->derivesEpsilon->contains($nonTerminal)) {
                $firstSets->addEpsilon($nonTerminal);
            }
        }

        //initialize the obvious first sets of productions which start with a terminal
        $productions = $g->getProductions();
        foreach ($productions as $production) {
            $symbol = $production->getFirstSymbol();
            if ($symbol->getType() == Symbol::TYPE_NON_TERMINAL) {
                $firstSets->addTerminal($production->getNonTerminal(), $symbol);
            }
        }

        //initialize first sets for productions which contain several non-terminals
        do {
            $changes = false;
            foreach ($productions as $production) {
                $expression = $production->getExpression();
                if ($expression instanceof ExpressionIterable) {
                    $rhs = $expression->toArray();
                    $updateSet = $this->stringFirstSet->compute($rhs, $firstSets);

                    $nonTerminal = $production->getNonTerminal();
                    $changes |= $firstSets->addAllTerminals($nonTerminal, $updateSet);
                }
            }
        } while($changes);

        return $firstSets;
    }

    /**
     * @param Grammar $g
     * @return PredictiveParsingSets
     */
    protected function createSets(Grammar $g)
    {
        $nonTerminals = $g->getNonTerminals();
        $sets = new PredictiveParsingSets($nonTerminals);

        return $sets;
    }
}
