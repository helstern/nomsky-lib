<?php namespace Helstern\Nomsky\Tokens;

class EofTokenDefinition
{
    /**
     * @return int
     */
    public function getType()
    {
        return DefaultTokenTypesEnum::ENUM_EOF;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return '';
    }

}
