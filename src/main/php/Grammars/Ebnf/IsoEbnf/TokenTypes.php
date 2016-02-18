<?php namespace Helstern\Nomsky\Grammars\Ebnf\IsoEbnf;

class TokenTypes {

    /**
     * ,
     */
    const ENUM_CONCATENATE = 30;

    /**
     * =
     * :
     */
    const ENUM_DEFINITION_LIST_START = 35;

    /**
     * |
     */
    const ENUM_DEFINITION_SEPARATOR = 40;

    /**
     * {
     */
    const ENUM_START_REPEAT = 45;

    /**
     * }
     */
    const ENUM_END_REPEAT = 50;

    /**
     * [
     */
    const ENUM_START_OPTION = 55;

    /**
     * ]
     */
    const ENUM_END_OPTION = 60;

    /**
     * (
     */
    const ENUM_START_GROUP = 65;

    /**
     * )
     */
    const ENUM_END_GROUP = 70;

    /**
     * ;
     * .
     */
    const ENUM_TERMINATOR = 85;

    /**
     * ?
     */
    const ENUM_SPECIAL_SEQUENCE = 100;

    /**
     * LETTER , { '_' | LETTER | DECIMAL_DIGIT  } .
     */
    const ENUM_IDENTIFIER = 105;

    /**
     * "[^"]+"
     */
    const ENUM_STRING_LITERAL = 110;

    /**
     * \(\*\s*\*\)|\(\*(?!\*\))(?:.|\n|\r)*?\*\)
     */
    const ENUM_COMMENT = 115;

    //composite tokens

    const ENUM_WS = 235;

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
