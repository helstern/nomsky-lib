<?php namespace Helstern\Nomsky\Tokens;

interface TokenDefinition
{
    /**
     * @return int
     */
    public function getType();

    /**
     * @return string
     */
    public function getValue();
}
