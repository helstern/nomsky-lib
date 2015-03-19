<?php namespace Helstern\Nomsky\Grammar\Converters;

use Helstern\Nomsky\Grammar\Converters\EliminateOptionals;
use Helstern\Nomsky\Grammar\Converters\EliminateGroups;
use Helstern\Nomsky\Grammar\Converters\EliminateAlternations;

use Helstern\Nomsky\Grammar\Converters\EliminateOptionals\IncrementalNamingStrategy;

use Helstern\Nomsky\Grammar\Grammar;
use Helstern\Nomsky\Grammar\Production\Production;

class EbnfToBnf
{
    /** @var array | ProductionTransformer[] */
    protected $transformers;

    public function __construct()
    {
        $this->transformers = array(
            new EliminateOptionals\ConversionTransformer(new IncrementalNamingStrategy()),
            new EliminateGroups\ConversionTransformer(),
            new EliminateAlternations\ConversionTransformer(),
        );
    }

    /**
     * @param Grammar $grammar
     * @return array|Production[]
     */
    public function convert(Grammar $grammar)
    {
        $originalProductions = $grammar->getProductions();
        $intermediaryProductionsList = array_reverse($originalProductions);

        foreach ($this->transformers as $transformer) {
            $tmpList = array();
            do {
                $production     = array_pop($intermediaryProductionsList);
                $processed      = $transformer->transform($production);

                $processed      = array_reverse($processed);
                $tmpList        = array_merge($processed, $tmpList);
            } while(!is_null(key($intermediaryProductionsList)));
            $intermediaryProductionsList = array_splice($tmpList, 0);
        }

        $convertedProductions = array_reverse($intermediaryProductionsList);
        return $convertedProductions;
    }
}
