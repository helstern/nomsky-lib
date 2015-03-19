<?php namespace Helstern\Nomsky\GrammarAnalysis\Sets;

use Helstern\Nomsky\Grammar\Production\Production;
use Helstern\Nomsky\Grammar\Symbol\SymbolSet;

class LookAheadSetEntry
{
    /** @var Production */
    protected $production;

    /** @var SymbolSet */
    protected $symbolSet;

    /**
     * @param Production $production
     * @param SymbolSet $symbolSet
     */
    public function __construct(Production $production, SymbolSet $symbolSet)
    {
        $this->production = $production;
        $this->symbolSet = $symbolSet;
    }

    public function getProduction()
    {
        return $this->production;
    }

    public function getSymbolSet()
    {
        return $this->symbolSet;
    }
}
