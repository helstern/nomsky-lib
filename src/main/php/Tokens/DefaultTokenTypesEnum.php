<?php namespace Helstern\Nomsky\Tokens;

class DefaultTokenTypesEnum
{

    /** end of file */
    const ENUM_EOF = 0;

    /** whitespace */
    const ENUM_WS = 0;

    public function toArray() {
        $reflection = new \ReflectionClass($this);
        $constants = $reflection->getConstants();

        return $constants;
    }

    /**
     * @param int $tokenType
     * @return bool
     */
    public function contains($tokenType)
    {
        return true;
    }
}
