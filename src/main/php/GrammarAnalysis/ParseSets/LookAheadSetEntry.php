<?php namespace Helstern\Nomsky\GrammarAnalysis\ParseSets;

use Helstern\Nomsky\Grammar\Production\Production;
use Helstern\Nomsky\Grammar\Symbol\SymbolSet;
use Helstern\Nomsky\GrammarAnalysis\Production\NormalizedProduction;

class LookAheadSetEntry
{
    /** @var Production */
    protected $key;

    /** @var SymbolSet */
    protected $value;

    /**
     * @param NormalizedProduction $production
     * @param SymbolSet $symbolSet
     */
    public function __construct(NormalizedProduction $production, SymbolSet $symbolSet)
    {
        $this->key = $production;
        $this->value = $symbolSet;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getValue()
    {
        return $this->value;
    }
}
