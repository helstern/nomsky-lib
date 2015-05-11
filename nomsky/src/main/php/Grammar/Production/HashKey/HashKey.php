<?php namespace Helstern\Nomsky\Grammar\Production\HashKey;

interface HashKey
{
    /**
     * @return string
     */
    public function toString();

    /**
     * @param HashKey $other
     * @return int
     */
    public function compare(HashKey $other);
}
