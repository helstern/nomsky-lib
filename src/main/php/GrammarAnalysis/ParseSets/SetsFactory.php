<?php namespace Helstern\Nomsky\GrammarAnalysis\ParseSets;

use Helstern\Nomsky\Grammar\Grammar;

class SetsFactory
{
    /**
     * Creates an empty parse set
     *
     * @param Grammar $g
     *
     * @return ParseSets
     */
    public function createEmptyParseSets(Grammar $g)
    {
        $nonTerminals = $g->getNonTerminals();
        $sets = new ParseSets($nonTerminals);

        return $sets;
    }

    /**
     * @return \Helstern\Nomsky\GrammarAnalysis\ParseSets\LookAheadSets
     */
    public function createEmptyLookAheadSets()
    {
        $hashFactory = new SimpleHashKeyFactory();
        $sets = new LookAheadSets($hashFactory);

        return $sets;
    }
}
