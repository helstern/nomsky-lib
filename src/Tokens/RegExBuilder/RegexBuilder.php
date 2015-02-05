<?php namespace Helstern\Nomsky\Tokens\RegExBuilder;

class RegexBuilder
{
    /**
     * @return RegexSequenceBuilder
     */
    public function sequence()
    {
        $instance = new RegexSequenceBuilder();
        $args = func_get_args();
        foreach ($args as $arg) {
            $instance->add($arg);
        }

        return $instance;
    }

    /**
     * @return RegexAlternativesBuilder
     */
    public function alternatives()
    {
        $instance =  new RegexAlternativesBuilder();
        $alternatives = func_get_args();
        foreach ($alternatives as $arg) {
            $instance->add($arg);
        }

        return $instance;
    }
}
