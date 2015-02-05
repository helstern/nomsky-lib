<?php namespace Helstern\Nomsky\Tokens;

use Helstern\Nomsky\Tokens\TokenPattern\RegexTokenPattern;

class TokenTypeEnum {

    /** end of file */
    const TYPE_EOF = 0;

    /** end of rule */
    const TYPE_EOR = 10;

    const TYPE_OPERATOR = 20;

    /**
     * ,
     */
    const TYPE_CONCATENATE = 30;

    /** =
     * :==
     */
    const TYPE_DEFINITION_LIST_START = 35;

    /**
     * |
     */
    const TYPE_DEFINITION_SEPARATOR = 40;

    /**
     * {
     */
    const TYPE_START_REPEAT = 45;

    /**
     * }
     */
    const TYPE_END_REPEAT = 50;


    /**
     * [
     */
    const TYPE_START_OPTION = 55;

    /**
     * ]
     */
    const TYPE_END_OPTION = 60;

    /**
     * (
     */
    const TYPE_START_GROUP = 65;

    /**
     * )
     */
    const TYPE_END_GROUP = 70;

    /**
     * ;
     * .
     */
    const TYPE_TERMINATOR = 75;

    /**
     * '
     */
    const TYPE_SINGLE_QUOTE = 80;

    /**
     * "
     */
    const TYPE_DOUBLE_QUOTE = 85;

    /**
     * ..
     */
    const TYPE_RANGE_OPERATOR = 90;


    //composite tokens

    const TYPE_LITERAL = 200;

    /**
     *
     */
    const TYPE_CHARACTER_LITERAL = 205;

    /**
     *
     */
    const TYPE_STRING_LITERAL = 210;

    /**
     *
     */
    const TYPE_CHARACTER_RANGE = 215;

    /**
     *
     */
    const TYPE_IDENTIFIER = 220;

    /**
     *
     */
    const TYPE_WS = 225;

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

    /**
     * @param int $tokenType
     * @param string $pattern
     * @throws \RuntimeException
     * @return RegexTokenPattern
     */
    public function buildRegexPattern($tokenType, $pattern)
    {
        if (! $this->contains($tokenType)) {
            throw new \RuntimeException('unknown token type ' . $tokenType);
        }

        $tokenPattern = new RegexTokenPattern($tokenType, $pattern);
        return $tokenPattern;
    }
}
