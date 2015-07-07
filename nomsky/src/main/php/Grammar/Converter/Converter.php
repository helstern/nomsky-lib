<?php namespace Helstern\Nomsky\Grammar\Converter;

use Helstern\Nomsky\Grammar\Grammar;
use Helstern\Nomsky\Grammar\Production\Production;

class Converter
{
    /** @var array | ProductionTransformer[] */
    protected $transformers;

    public function __construct(array $transformers)
    {
        $this->transformers = $transformers;
    }

    /**
     * @param Grammar $g
     * @return array|Production[]
     */
    public function convert(Grammar $g)
    {
        $productions = $g->getProductions();
        $intermediaryProductionsList = array_reverse($productions);

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

        return $intermediaryProductionsList;
    }
}
