<?php namespace Helstern\Nomsky\Lexer;

class NomskyTokenTypesEnum {

    /** end of file */
    const ENUM_EOF = 0;

    /** end of rule */
    const ENUM_EOR = 10;

    /**
     * ,
     */
    const ENUM_CONCATENATE = 30;

    /**
     * =
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
     * ..
     */
    const ENUM_RANGE_OPERATOR = 95;


    //composite tokens

    const ENUM_LITERAL = 200;

    /**
     *
     */
    const ENUM_CHARACTER_LITERAL = 205;

    /**
     *
     */
    const ENUM_STRING_LITERAL = 210;

    /**
     *
     */
    const ENUM_COMMENT_LITERAL = 215;

    /**
     *
     */
    const ENUM_CHARACTER_RANGE = 220;

    /**
     *
     */
    const ENUM_IDENTIFIER = 225;

    /**
     *
     */
    const ENUM_WS = 230;

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
