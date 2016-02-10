<?php namespace Helstern\Nomsky\Grammar\Converter;

use Helstern\Nomsky\Grammar\Production\Production;

interface ProductionTransformer
{
    /**
     * @param Production $production
     * @return array|Production[]
     */
    public function transform(Production $production);
}
