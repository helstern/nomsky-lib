<?php namespace Helstern\Nomsky\Grammar\Production\HashKey;

class SimpleHashKey implements HashKey
{
    /** @var   */
    protected $stringHash;

    public function __construct($stringHash)
    {
        $this->stringHash = $stringHash;
    }
    /**
     * @return string
     */
    public function toString()
    {
        return $this->stringHash;
    }

    /**
     * @param HashKey $other
     * @return int
     */
    public function compare(HashKey $other)
    {
        return strcmp($this->stringHash, $other->toString());
    }
}
