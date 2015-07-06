<?php namespace Helstern\Nomsky\Grammar\Production\Set;

use Helstern\Nomsky\Grammar\Production\HashKey\HashKey;
use Helstern\Nomsky\Grammar\Production\Production;

class ProductionSetEntry
{
    /** @var HashKey */
    protected $hashKey;

    /** @var Production */
    protected $production;

    /**
     * @param HashKey $hashKey
     * @param Production $production
     */
    public function __construct(HashKey $hashKey, Production $production)
    {
        $this->hashKey = $hashKey;
        $this->production = $production;
    }

    /**
     * @return HashKey
     */
    public function getHashKey()
    {
        return $this->hashKey;
    }

    /**
     * @return Production
     */
    public function getProduction()
    {
        return $this->production;
    }
}
