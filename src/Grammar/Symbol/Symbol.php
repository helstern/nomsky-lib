<?php namespace Helstern\Nomsky\Grammar\Symbol;

interface Symbol
{
    const TYPE_TERMINAL = 0;

    const TYPE_NON_TERMINAL = 0;

    /**
     * @return int
     */
    public function getType();

    /**
     * @return string
     */
    public function hashCode();
}
