<?php namespace Helstern\Nomsky\Lexers\EbnfLexer;

class TokenTypesEnum {

    /** end of file */
    const ENUM_EOF = 0;

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
     * (*
     */
    const ENUM_START_COMMENT = 75;

    /**
     * *)
     */
    const ENUM_END_COMMENT = 80;

    /**
     * ;
     * .
     */
    const ENUM_TERMINATOR = 85;

    /**
     * '
     */
    const ENUM_SINGLE_QUOTE = 90;

    /**
     * "
     */
    const ENUM_DOUBLE_QUOTE = 95;

    /**
     * ?
     */
    const ENUM_SPECIAL_SEQUENCE = 100;

    /**
     * _
     */
    const ENUM_ID_SEPARATOR = 105;


    /**
     * a-zA-Z
     */
    const ENUM_LETTER = 110;

    /**
     * 0-9
     */
    const ENUM_DECIMAL_DIGIT = 115;

    /**
     *
     */
    const OTHER_CHARACTER = 200;

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
