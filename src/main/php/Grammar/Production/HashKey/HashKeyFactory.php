<?php namespace Helstern\Nomsky\Grammar\Production\HashKey;

use Helstern\Nomsky\Grammar\Production\Production;

interface HashKeyFactory
{
    /**
     * @param Production $production
     * @return HashKey
     */
    public function hash(Production $production);
}
