<?php namespace Helstern\Nomsky\RegExBuilder;

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

    /**
     * @param $pattern
     * @return RegexPatternBuilder
     */
    public function pattern($pattern)
    {
        return new RegexPatternBuilder($pattern);
    }

    /**
     * @param $pattern
     * @return RegexPatternBuilder
     */
    public function negativeLookAhead($pattern)
    {
        $regexBuilder = RegexPatternBuilder::newInstance($pattern);
        $regexBuilder->negativeLookAhead();

        return $regexBuilder;
    }

    /**
     * @param $string
     * @return RegexCharacterSetBuilder
     */
    public function characterSet($string)
    {
        return RegexCharacterSetBuilder::newInstanceFromString($string);
    }

    /**
     * @param string $rangeStart
     * @param string $rangeEnd
     * @return RegexPatternBuilder
     */
    public function characterRange($rangeStart, $rangeEnd)
    {
        return RegexCharacterSetBuilder::newInstanceFromRange($rangeStart, $rangeEnd);
    }
}
