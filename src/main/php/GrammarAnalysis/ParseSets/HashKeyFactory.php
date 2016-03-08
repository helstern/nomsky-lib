<?php namespace Helstern\Nomsky\GrammarAnalysis\ParseSets;

use Helstern\Nomsky\GrammarAnalysis\Production\NormalizedProduction;

interface HashKeyFactory
{
    /**
     * @param NormalizedProduction $production
     * @return string
     */
    public function hash(NormalizedProduction $production);
}
