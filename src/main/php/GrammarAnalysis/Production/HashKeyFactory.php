<?php namespace Helstern\Nomsky\GrammarAnalysis\Production;

interface HashKeyFactory
{
    /**
     * @param NormalizedProduction $production
     * @return string
     */
    public function hash(NormalizedProduction $production);
}
