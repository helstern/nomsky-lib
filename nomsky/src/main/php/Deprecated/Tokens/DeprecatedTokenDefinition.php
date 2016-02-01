<?php namespace Helstern\Nomsky\Tokens;

interface DeprecatedTokenDefinition
{
    /** end of file */
    const TYPE_EOF = 0;

    /**
     * @return int
     */
    public function getType();

    /**
     * @return string
     */
    public function getValue();
}
