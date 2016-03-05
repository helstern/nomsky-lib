<?php namespace Helstern\Nomsky\Grammar;

use Helstern\Nomsky\Grammar\Converter\Converter;
use Helstern\Nomsky\Grammar\Converter\ProductionTransformer;
use Helstern\Nomsky\Grammar\Transformations\EliminateOptionals;
use Helstern\Nomsky\Grammar\Transformations\EliminateGroups;
use Helstern\Nomsky\Grammar\Transformations\EliminateChoice;

use Helstern\Nomsky\Grammar\Production\Production;

class Conversions
{
    /**
     * @return array|ProductionTransformer[]
     */
    public function createEbnfToBnfTransformationsList()
    {
        return [
            new EliminateOptionals\Transformer(new EliminateOptionals\IncrementalNamingStrategy()),
            new EliminateGroups\Transformer(),
            new EliminateChoice()
        ];
    }


    /**
     * @param Grammar $grammar
     * @return array|Production[]
     */
    public function ebnfToBnf(Grammar $grammar)
    {
        $list = $this->createEbnfToBnfTransformationsList();
        $converter = new Converter($list);

        $bnf = $converter->convert($grammar);
        return $bnf;
    }
}
