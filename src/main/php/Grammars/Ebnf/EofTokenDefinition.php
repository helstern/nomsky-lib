<?php namespace Helstern\Nomsky\Grammars\Ebnf;

use Helstern\Nomsky\Grammars\Ebnf\IsoEbnfTokens\TokenTypesEnum;
use Helstern\Nomsky\Tokens\TokenDefinition;

class EofTokenDefinition implements TokenDefinition
{
    /**
     * @return int
     */
    public function getType()
    {
        return TokenTypesEnum::ENUM_EOF;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return '';
    }

}
