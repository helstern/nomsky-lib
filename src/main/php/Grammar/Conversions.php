<?php namespace Helstern\Nomsky\Grammar;

use Helstern\Nomsky\Grammar\Converter\Converter;
use Helstern\Nomsky\Grammar\Converter\ProductionTransformer;
use Helstern\Nomsky\Grammar\Transformations;

use Helstern\Nomsky\Grammar\Production\Production;

class Conversions
{
    /**
     * @return array|ProductionTransformer[]
     */
    public function createEbnfToBnfTransformationsList()
    {
        return [
            new Transformations\EliminateOptionals\Transformer(new Transformations\EliminateOptionals\IncrementalNamingStrategy()),
            new Transformations\EliminateGroups\Transformer(),
            new Transformations\EliminateNesting\Transformer(),
            new Transformations\EliminateChoice()
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
