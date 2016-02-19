<?php namespace Helstern\Nomsky\Grammar;

use Helstern\Nomsky\Grammar\Converter\Converter;
use Helstern\Nomsky\Grammar\Transformations\EliminateOptionals;
use Helstern\Nomsky\Grammar\Transformations\EliminateGroups;
use Helstern\Nomsky\Grammar\Transformations\EliminateChoice;

use Helstern\Nomsky\Grammar\Production\Production;

class Conversions
{
    /**
     * @return Converter
     */
    public function createEbnfToBnfConverter() {

        $transformers = array(
            new EliminateOptionals\Transformer(new EliminateOptionals\IncrementalNamingStrategy()),
            new EliminateGroups\Transformer(),
            new EliminateChoice()
        );

        $converter = new Converter($transformers);
        return $converter;
    }


    /**
     * @param Grammar $grammar
     * @return array|Production[]
     */
    public function ebnfToBnf(Grammar $grammar)
    {
        $converter = $this->createEbnfToBnfConverter();
        $bnf = $converter->convert($grammar);

        return $bnf;
    }
}
