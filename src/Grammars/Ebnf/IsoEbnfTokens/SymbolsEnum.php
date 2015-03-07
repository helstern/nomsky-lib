<?php namespace Helstern\Nomsky\Grammars\Ebnf\IsoEbnfTokens;

class SymbolsEnum
{
    const ENUM_CONCATENATE = ',';

    const ENUM_DEFINE = '=';

    const ENUM_DEFINE_ALT_ONE = ':==';

    const ENUM_DEFINE_ALT_TWO = ':';

    const ENUM_DEFINITION_SEPARATOR = '|';

    const ENUM_START_REPEAT = '{';

    const ENUM_END_REPEAT = '}';

    const ENUM_START_OPTION = '[';

    const ENUM_END_OPTION = ']';

    const ENUM_START_GROUP = '(';

    const ENUM_END_GROUP = ')';

    const ENUM_START_COMMENT = '(*';

    const ENUM_END_COMMENT = '*)';

    const ENUM_TERMINATOR = ';';

    const ENUM_TERMINATOR_ALT_ONE = '.';

    const ENUM_SINGLE_QUOTE = "'";

    const ENUM_DOUBLE_QUOTE = '"';

    const ENUM_SPECIAL_SEQUENCE_DELIMITER = '?';

    const ENUM_EXCEPT = '-';

}
